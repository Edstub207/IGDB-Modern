/* Copyright Cubell 2016 LR 1.2 */
(function( $ ) {
	'use strict';
	var cbBodyRTL = false,
		cbWindow = $(window),
		cbBody = $('body'),
		cbUserRating = $('[id^="cb-user-rating-"]'),
		cbReviewBox = $('[id^="cb-review-box-"]'),
		cbReviewBoxBars = cbReviewBox.find('.cb-overlay span'),
		cbUSerRatingCookie = Cookies.get('lets_review_user_rating'),
		cbGalleryWrap = $('#cb-review-gallery-wrap'),
		cbUserRatingTitle, cbUserRatingScore, cbUserRatingScoreCache, cbUserRatingWidth, cbBaseX, cbUserRatingLocation, cbOverlaySpan, cbUserRatingLocationOffset, cbUserRatingWidthCache, cbYourRatingText, cbUserRatingTitleCache, cbUserRatingPId, cbThis, cbReviewParent, cbHover, cbParent, cbValue, cbReviewFormat, cbReviewer, cbReviewerFS, cbOverlaySpanCalc, cbScoreType, cbCircleValue, cbOutOf5;

	if ( $('body').hasClass('rtl') ) { cbBodyRTL = true; }
	if ( !!$.prototype.swipebox ) {
	    $( '.lets-review-lightbox' ).swipebox( {
			useCSS : true, // false will force the use of jQuery for animations
			useSVG : false, // false to force the use of png for buttons
			initialIndexOnArray : 0, // which image index to init when a array is passed
			hideCloseButtonOnMobile : false, // true will hide the close button on mobile devices
			removeBarsOnMobile : true, // false will show top bar on mobile devices
			hideBarsDelay : 0, // delay before hiding bars on desktop
			videoMaxWidth : 1140, // videos max width
			beforeOpen: function() {}, // called before opening
			afterOpen: null, // called after opening
			afterClose: function() {}, // called after closing
			loopAtEnd: false // true will return to the first image after the last image is reached
		} );

		cbBody.on('click touchend','#swipebox-slider .current img', function(){
			return false;
		}).on('click touchend','#swipebox-slider .current', function(){
			$('#swipebox-close').trigger('click');
		});
	}

	var cbReviewTrigger = function() {
	  	$.each(cbReviewBoxBars, function() {
			cbValue = $(this);
	        if ( cbValue.visible(true) ) {
	        	cbParent = cbValue.closest('[id^="cb-review-box-"]');
				cbScoreType = cbParent.data('cb-score-format');
	            cbValue.removeClass('cb-trigger-zero');
	            switch ( cbScoreType ) {
				    case 1:
				        cbValue.addClass('cb-trigger');
						break;
					case 2:
				        cbValue.addClass('cb-trigger');
						break;	
				    default:
				        cbValue.addClass('cb-5-trigger');
				}
	        }
	    });
	} 

	cbReviewTrigger();
	cbWindow.scroll(cbReviewTrigger);

    if ( cbUserRating.length ) {

    	cbUserRating.each(function() {
    		cbThis = $(this);
    		cbReviewParent = cbThis.closest('[id^="cb-review-box-"]');
			cbUserRatingPId = cbReviewParent.data('cb-pid');
			cbReviewFormat = cbReviewParent.data('cb-score-format');
			cbReviewer = cbReviewParent.data('cb-reviewer');
			cbReviewerFS = cbReviewParent.find('.cb-score-box');

			switch(cbReviewFormat) {
			    case 1:
			        cbHover = cbThis.find('.cb-bar');
			        break;
			    case 2:
			        cbHover = cbThis.find('.cb-bar');
			        break;
			    default:
			        cbHover = cbThis.find('.cb-overlay');
			}			
			
			if ( cbThis.hasClass( 'cb-rated' ) || ( ( cbUSerRatingCookie instanceof Array ) && ( cbUSerRatingCookie.indexOf( cbUserRatingPId ) > -1 ) ) ) {
				return;
			}

			cbHover.on('mousemove click mouseleave mouseenter', function(e) {
				cbThis = $(this).closest('[id^="cb-user-rating-"]');

				cbReviewParent = cbThis.closest('[id^="cb-review-box-"]');
				cbUserRatingPId = cbReviewParent.data('cb-pid');
				cbReviewFormat = cbReviewParent.data('cb-score-format');

				if ( e.type == 'mouseenter' ) {
					cbUserRatingTitle = cbThis.find('.cb-criteria');
					cbUserRatingTitleCache = cbUserRatingTitle.html();
					cbUserRatingScore = cbThis.find('.cb-criteria-score');
					cbUserRatingScoreCache = cbUserRatingScore.data('cb-score-cache');
					cbUserRatingWidthCache = cbUserRatingScore.data('cb-width-cache');
					cbUserRatingLocation = cbThis.find('.cb-overlay');
					cbUserRatingLocationOffset = cbUserRatingLocation.offset();
					cbUserRatingWidth = cbUserRatingLocation.find('> span');
		            cbUserRatingTitle.html( cbUserRatingTitle.data('cb-txt') );
		        }
				
				cbBaseX = Math.ceil( ( e.pageX - cbUserRatingLocationOffset.left ) / ( cbUserRatingLocation.width() / 100 ) );
			
	            if ( cbBodyRTL === true ) {
	                cbOverlaySpan = ( 100 - cbBaseX );
	            } else {
	                cbOverlaySpan = cbBaseX;
	            }

	            if ( cbOverlaySpan > 100 ) { cbOverlaySpan = 100; }
        		if ( cbOverlaySpan < 1 ) { cbOverlaySpan = 0; }

        		switch(cbReviewFormat) {
				    case 1:
				        cbOverlaySpanCalc = cbOverlaySpan;
				        cbOutOf5 = false;
				        break;
				    case 2:
				        cbOverlaySpanCalc = Math.round( cbOverlaySpan * 10 ) / 100;
				        cbOutOf5 = false;
				        break;
				    default:
				        cbOverlaySpanCalc = Math.round( ( cbOverlaySpan / 20 ) * 10 ) / 10;
				        cbOutOf5 = true;
				}

				if ( cbReviewParent.hasClass('cb-out-of-5') ) {
					cbUserRatingWidth.stop().animate( { 'width' : (100 - cbOverlaySpan) + '%'}, 15);
				} else {
					cbUserRatingWidth.stop().animate( { 'width' : cbOverlaySpan + '%'}, 15);
				}
        		
        		cbUserRatingScore.html( cbOverlaySpanCalc );
        		
        		if ( ( e.type == 'mouseleave' ) || ( e.type == 'click' ) ) {
        			if ( cbReviewParent.hasClass('cb-out-of-5') ) {
						cbUserRatingWidth.stop().animate( { 'width' : (100 - cbUserRatingWidthCache) + '%' }, 200);
					} else {
						cbUserRatingWidth.stop().animate( { 'width' : cbUserRatingWidthCache + '%' }, 200);
					}
        			
        			cbUserRatingScore.html( cbUserRatingScoreCache );
        			cbUserRatingTitle.html (cbUserRatingTitleCache );
        		}

        		if ( e.type == 'click' ) {
		            $(this).off('mousemove click mouseleave mouseenter');
		            cbThis.addClass('cb-rated cb-lr-tip-f');
		            $.ajax({
		                type : "POST",
		                data : { action: 'lets_review_a_cb', letsReviewNonce: letsReview.letsReviewNonce, letsReviewScore: cbOverlaySpan, letsReviewPostID: cbUserRatingPId },
		                url  : letsReview.letsReviewAUrl,
		                dataType:"json",
		                beforeSend: function() {
		                	cbThis.addClass('cb-voting');
		                	if ( ( cbReviewer === 3 ) && ( cbOutOf5 === false ) ) {
		                		cbReviewerFS.addClass('cb-fs-voting');
		                	}
						},
		                success : function( data ){
		                	cbUserRatingLocation.find('> span').css('width', ( data[0] + '%' ) );
		                	cbThis.find('.cb-votes-count').html( data[2] );
		                	cbUserRatingScore.html( data[1] );
		                    Cookies.set('lets_review_user_rating', data[3], { expires: 7 });
		                    cbThis.removeClass('cb-voting');
		                    if ( ( cbReviewer === 3 ) && ( cbOutOf5 === false ) ) {
		                    	cbReviewerFS.find('.cb-final-score').html( data[1] );
		                    	cbReviewerFS.removeClass('cb-fs-voting');
		                    }
		                    cbThis.tipper({
						        direction: 'bottom',
						        follow: true
						    });
		                },
		                error : function(jqXHR, textStatus, errorThrown) {
		                    console.log("cbur " + jqXHR + " :: " + textStatus + " :: " + errorThrown);
		                }
		            });

		            return false;
        		}

			});

    	});

    }

    $('.cb-lr-tip-f').tipper({
        direction: 'bottom',
        follow: true
    });
	
})( jQuery );