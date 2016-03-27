"use strict";

jQuery(document).ready(function($) {

	
	function baseVote(value){
		
	}

	function enablePostVoting(){
		$('.ht-voting-links a').each(function( index ) {
            var voteActionAnchor = $(this);
            var enabled = voteActionAnchor.hasClass('enabled');
            var targetDirection = voteActionAnchor.attr('data-direction');
            var targetType = voteActionAnchor.attr('data-type');
            var targetNonce = voteActionAnchor.attr('data-nonce');
            var targetID = voteActionAnchor.attr('data-id');
            var targetAllow = voteActionAnchor.attr('data-allow');
            var targetDisplay = voteActionAnchor.attr('data-display');
            voteActionAnchor.click(function(event){
                event.preventDefault();
                if(!enabled){
                    alert(voting.log_in_required)
                    return;
                }
                var data = {
                  	action: 'ht_voting',
                   	direction: targetDirection,
		            type: targetType,
		            nonce: targetNonce,
		            id: targetID,
                    allow: targetAllow,
                    display: targetDisplay,
                };
                $.post(voting.ajaxurl, data, function(response) {
                  if(response!=''){
                    //replace the voting box with response
                    if(targetType=="post"){
                    	$('#ht-voting-post-'+targetID).replaceWith(response);
                        animateVoteComment('#ht-voting-post-'+targetID);
                    }else if(targetType=="comment"){
                        $('#ht-voting-comment-'+targetID).slideUp(100);
                    	$('#ht-voting-comment-'+targetID).replaceWith(response);
                        $('#ht-voting-comment-'+targetID).slideDown(1000);
                    }
                    enablePostVoting();
                    enableVoteFeedback();
                  }
                });
                
            }); 

        });
    }
    //onload enable buttons
    enablePostVoting();


    function enableVoteFeedback(){
        $('.ht-voting-comment').each(function( index ) {
            var voteCommentAnchor = $(this);
            
            var targetVoteKey = voteCommentAnchor.attr('data-vote-key');
            var enabled = voteCommentAnchor.hasClass('enabled');
            var targetNonce = voteCommentAnchor.attr('data-nonce');
            var targetID = voteCommentAnchor.attr('data-id');

            var submitButton = voteCommentAnchor.children('button');
            
            submitButton.click(function(event){
                var feedback = voteCommentAnchor.children('textarea').val();
                event.preventDefault();
                if(!enabled){
                    alert(voting.log_in_required);
                    return;
                }
                var data = {
                    action: 'ht_voting_update_feedback',
                    key: targetVoteKey,
                    nonce: targetNonce,
                    comment: feedback,
                    id: targetID,
                };
                $.post(voting.ajaxurl, data, function(response) {
                  if(response!=''){
                    voteCommentAnchor.html(response);
                  }
                });
                
            }); 

        });
    }

    function animateVoteComment(id){
        var parentVoteId = $(id);
        var commentArea = parentVoteId.find('.ht-voting-comment').first();
        //initially hide
        commentArea.hide();
        //then show
        commentArea.slideDown(1000);        
    }

	

});