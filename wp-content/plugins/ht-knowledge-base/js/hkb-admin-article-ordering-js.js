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
                stopSortingArticles();
            },
        });

    //get selected category term id
    function getCategoryTermIDSelected(){
        return $('select.hkb-cat-selector-adm option:selected').val();
    }

    //initial load
    setSelectedCategory(getCategoryTermIDSelected());
    //dropdown change
    $('select.hkb-cat-selector-adm').change(function(e){
        setSelectedCategory(getCategoryTermIDSelected());
    });

    function setSelectedCategory(termID){
        $('.hkb-category-article-list').each(  function(){
            var currentArticleList = $(this);
            var articleTermID = currentArticleList.attr('data-term-id');
            if(termID!=articleTermID){
                $('.hkb-cat-label-'+articleTermID).hide();
                currentArticleList.hide();
            } else {
                $('.hkb-cat-label-'+articleTermID).show();
                currentArticleList.show();
            }
        });
    }

    //called when the category order is sorted
    function stopSortingArticles(){
        //no longer saves on stop sorting
    }


    function saveOrder(){
        var currentTermID = getCategoryTermIDSelected();
        //loop over 
        var items = [];
        $("ul.hkb-category-article-list-" + currentTermID).each(  function(){
            var liElements = $(this).children('li');

            for (var i = 0; i < liElements.length; i++) {
                var currentLi = $(liElements[i]);
                var articleID = currentLi.attr('data-article-id');
                var newOrder = (i*10)+10;
                currentLi.attr('data-order', newOrder);
                //add this item to our list of articles
                items.push({articleID: articleID, termID: currentTermID, order: newOrder});
            };

        } );

        //save the new order                                                            
        $.post( 
            ajaxurl, 
            {   action:'save_ht_kb_article_order', 
                items: items, 
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
    }

    //bind the save order click action
    $(".save-order").bind( "click", function() {  
        saveOrder();            
    });

});



