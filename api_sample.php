<?php
namespace Wcp;
require_once '/home/xxxxxx/env_settings.php';

// WCP constant definition will be removed when they get defined in global settings
define ('WCP_HOST', 'https://xxxxxx.xxxxxx.com/cp/api.php');
define ('WCP_ADMIN_USER', 'xxxxxx');
define ('WCP_ADMIN_PASS', 'xxxxxx');

/**
 * class to handle web cast pro API functions
 * @author Isaac Zhao <izhao@corp.acesse.com>
 * @name Wcp.php
 * @version 1.0.0 Aug 11, 2015
 */
class Wcp {
    /**
     * create a new web cast pro host account
     * @param $_sName - string, WCP account name
     * @param $_sPass - string, WCP account password
     * @param $_sRoom - string, WCP room name, default ''
     * @param $_sEmail - string, email
     * @param $_iUsers - integer, number of users allowed for the conference
     * @param $_iDocQuota - integer, number of document quota, default 0
     * @param $_iVQuota - integer, video quota, default 0
     * @param $_iVSize - integer, video size, default 10 
     * @return integer, 0 success, -1 failure
     */    
    public function create_acc($_sName, $_sPass, $_sRoom, $_sEmail, $_iUsers = 10, $_iDocQuota = 0, $_iVQuota = 0, $_iVSize = 10) {
        $arrQuery = array (
            'usr' => WCP_ADMIN_USER,
            'pwd' => WCP_ADMIN_PASS,
            'action' => 'createDefaultHostAccount',
            'accountName' => $_sName,
            'password' => $_sPass,
            'conferenceName' => $_sRoom,
            'email' => $_sEmail,
            'maxLoggedInUsers' => $_iUsers,
            'documentCenterQuota' => $_iDocQuota,
            'presentVideoQuota' => $_iVQuota,
            'presentVideoMaxFLVSize' => $_iVSize
        );
        return $this->make_request ($arrQuery); 
    }

    /**
     * update a web cast pro host account
     * @param $_iAccID - web cast pro account ID, generated at account creation
     * @param $_sRoom - string, room name, default ''
     * @param $_iUsers - integer, number of users allowed for the conference
     * @param $_iDocQuota - integer, number of document quota, default 0
     * @param $_iVideoQuota - integer, video quota, default 0
     * @param $_iVideoSize - integer, video size, default 10 
     * @return integer, 0 success, -1 failure
     */    
    public function update_acc($_iAccID, $_sRoom = '', $_iUsers = 10, $_iDocQuota = 0, $_iVQuota = 0, $_iVSize = 10) {
         $arrQuery = array (
            'usr' => WCP_ADMIN_USER,
            'pwd' => WCP_ADMIN_PASS,
            'action' => 'updateHostSettings',
            'accountID' => $_iAccID,
            'conferenceName' => $_sRoom,
            'maxLoggedInUsers' => $_iUsers,
            'documentCenterQuota' => $_iDocQuota,
            'presentVideoQuota' => $_iVQuota,
            'presentVideoMaxFLVSize' => $_iVSize
        );
        return $this->make_request ($arrQuery); 
    }

    /**
     * toggle active status of a new web cast pro host account
     * @param $_iAccID - integer, web cast pro account ID, generated at account creation
     * @param $_iActive - integer, 1 to activate, 0 to deactivate, default deactivate
     * @return integer, 0 success, -1 failure
     */    
    public function toggle_acc($_iMemberID, $_iActive = 0) {
        require_once ("../includes/config.php");
        $db = new myq ();
        // get the AM email 
        $sEmail = $db->GetOne ("SELECT email FROM members WHERE member_id  $_iMemberID"); 
        // get the account ID
        $iAccID = retrieve_acc ('API_test_' . $_iMemberID, $sEmail); 
        $arrQuery = array (
            'usr' => WCP_ADMIN_USER,
            'pwd' => WCP_ADMIN_PASS,
            'action' => 'enableOrDisableHostAccount',
            'accountID' => $iAccID,
            'active' => $_iActive
        );
        return $this->make_request ($arrQuery); 
    }

    /**
     * delete a web cast pro host account
     * @param $_iAccID - web cast pro account ID, generated at account creation
     * @return integer, 0 success, -1 failure
     */    
    public function delete_acc($_iAccID) {
        $arrQuery = array (
            'usr' => WCP_ADMIN_USER,
            'pwd' => WCP_ADMIN_PASS,
            'action' => 'removeHostAccount',
            'accountID' => $_iAccID
        );
        return $this->make_request ($arrQuery); 
    }
    
    /**
     * retrieve member ID on WCP server
     * @param $_sUserName - string, username
     * @param $_sEmail - string, email
     * @return integer, 0 success, -1 failure
     */    
    public function retrieve_acc($_sUserName, $_sEmail) {
        $arrQuery = array (
            'usr' => WCP_ADMIN_USER,
            'pwd' => WCP_ADMIN_PASS,
            'action' => 'getHostInfo',
            'email' => $_sEmail,
            'username' => $_sUserName
        );
        $sAccInfo = $this->make_request ($arrQuery); 
        define ('ACCOUNT_NO_PREFIX', 10); 
        return substr (explode ('&', $sAccInfo)['0'], ACCOUNT_NO_PREFIX); 
    }

    /**
     * call remote API
     * @param $_sQuery - string, query for remote
     * @return integer, 0 success, -1 failure
     */    
    private function make_request($_sQuery) {
        $sQuery = WCP_HOST . '?' .  http_build_query($_sQuery);
        $curl = curl_init();
        curl_setopt_array($curl, array(
           CURLOPT_RETURNTRANSFER => 1,
           CURLOPT_URL => $sQuery
        ));
        $response = curl_exec ($curl); 
        return $response; 
    }
}
