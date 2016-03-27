"use strict";

jQuery(document).ready(function($) {

    //activate sortable lists
    $("ul.sortable").sortable({
            'tolerance':'intersect',
            'cursor':'pointer',
            'items':'> li',
            'axi': 'y',
            'placeholder':'placeholder',
            'nested': 'ul',
            stop: function( event, ui ) {
                stopSortingCategories();
            },
        });

    //called when the category order is sorted
    function stopSortingCategories(){
        $(".sortable").each(  function(){

            var liElements = $(this).children('li');

            for (var i = 0; i < liElements.length; i++) {
                var currentLi = $(liElements[i]);
                currentLi.attr('data-term-order', i);
            };         
        });
    }

    //bind the save order click action
    $(".save-order").bind( "click", function() {  
        //update the term-order based on the new sortable order        
        var terms = {};
        $(".sortable").each(  function(){            
            var liElements = $(this).children('li');            
            for (var i = liElements.length - 1; i >= 0; i--) {
                var currentLi = $(liElements[i]);
                var termID = currentLi.attr('data-term-id');
                var termOrder = currentLi.attr('data-term-order');
                terms[termID] = termOrder;
            };            
        });       

        //save the new order                                                            
        $.post( 
                ajaxurl, 
                {   action:'save_ht_kb_category_order', 
                    order: terms, 
                    security: framework.ajaxnonce 
                }, 
                function(data, textStatus, jqXHR) {
                        try{
                            if(data.state == "success"){
                                $("#ajax-response").html('<div class="message updated fade"><p>'+data.message+'</p></div>');
                            } else {
                                $("#ajax-response").html('<div class="message error fade"><p>'+data.message+'</p></div>');
                            }
                        } catch(err){
                            $("#ajax-response").html('<div class="message error fade"><p>'+framework.ajaxerror+'</p></div>');
                        }                      
                        
                        $("#ajax-response div").delay(3000).hide("slow");
                    },
                'json'
            );       

    });

});



