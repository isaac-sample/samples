...
    var oAjaxResult = $.ajax({
        type : "GET",
        url : "ajax.php",
        data : "request1=all&iRowCount="+__iRowCount,
        cache: true,
        success: function (sJSON) {
            var aJSON = JSON.parse(sJSON);    // convert string to array, containing 4 combos, grid, and language
            var aJSON1 = aJSON.slice();       // make a copy of it, so I can delete the used one, leave only the grid and summary in the end, normal copy == will be reference, not by value
            var bShowDataGrid = 0;            // avoid calling the function > 1 times, should be just 1 time
            $.each (aJSON, function (i, element) { 
                if (element.item_id1) {
                    __arrItemNames.push(element.item_name1); __arrItemIDs.push(element.item_id1); // put into 2 arrays
                    var recItem1 = {itemid: element.item_id1, itemname: element.item_name1}
                    __arrItems.push (recItem1); // works great, multiple records pushed into the array
                    index1 = aJSON1.indexOf(element); aJSON1.splice(index1, 1);  // remove this element, not to be used later
                } 
                else if (element.curr_id1) {
                    __arrCurrNames.push(element.curr_name1); __arrCurrIDs.push(element.curr_id1); // put into 2 arrays
                    index1 = aJSON1.indexOf(element); aJSON1.splice(index1, 1);  // remove this element, not to be used later
                } 
            })  // each element of the JSON array

            // the global arrays are constructed and ready to be used
            ini_group_combos(); 
            $('#myGrid').height(idea_grid_height(__iRowCount)); // first time, or user change row count in setting form 

            update_language_display(__aLang);   // update language display with the generated global language array, well done Isaac, may not need to be global?
            $( "#ac_item1" ).autocomplete({delay:0, source: __arrItemNames}); $( "#ac_curr1" ).autocomplete({delay:0, source: __arrCurrNames});
            $( "#ac_supp1" ).autocomplete({delay:0, source: __arrSuppNames}); $( "#ac_unit1" ).autocomplete({delay:0, source: __arrUnitNames});
        }   // end of the success return; 
    });     // end of the ajax get call
...

