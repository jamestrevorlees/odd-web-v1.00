"use strict";

jQuery(document).ready(function($) {
    


    $('button.ht-voting-delete-vote').each(function( index ) {
        var deleteButton = $(this);
        var postID = deleteButton.attr('data-post-id');
        var voteKey = deleteButton.attr('data-vote-key');
        var parentRow = deleteButton.parents('tr');
        var ajaxnonce = voting.ajaxnonce;
        var challenge = voting.deleteChallenge;
        deleteButton.click(function(event){
                event.preventDefault();
                var c = confirm(challenge);
                if (c != true) {
                    return;
                } 
                var oldText = deleteButton.text();
                deleteButton.html('<img src="' + voting.spinner + '"/>');
                //mark row for deletion
                parentRow.addClass('delete-row');
                var data = {
                    action: 'ht_voting_delete_vote',
                    key: voteKey,
                    nonce: ajaxnonce,
                    id: postID,
                };
                $.post(voting.ajaxurl, data, function(response) {
                    if('ok'==response){
                        console.log('removed vote');
                        //remove the row
                        parentRow.animate({ backgroundColor: "#969696" }, 2000, function () {
                            dataTable.row('.delete-row').remove().draw( false );
                        });
                    } else {
                        console.log('problem removing vote');
                        //unmark row for deletion
                        parentRow.removeClass('delete-row');
                        deleteButton.text(oldText);
                    }
                    
                    
                });
        });
    });

    $('.ht-voting-article-voting-actions button').each(function( index ) {
        var actionButton = $(this);
        var actionHref = actionButton.attr('href');
        var challenge = actionButton.attr('data-challenge');

        actionButton.click(function(event){
            event.preventDefault();
            var c = confirm(challenge);
            if (c == true) {
                window.location.href = actionHref;
            } 
            
        });
    });

    //finally - init datatables
    var dataTable = $('.ht-voting-backend-vote-list').DataTable();


});