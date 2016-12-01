(function( $ ) {
	'use strict';
	var cbDebug = false,
		cbWindow = $( window ),
		cbDoc = $ ( document ),
		cbAdminTabs = $('#cb-tabs'),
		cbAdminTabsA = cbAdminTabs.find('a'),
		cbTabContent = $('#cb-tab-content'),
		cbTabContentChildren = cbTabContent.children(),
		cbAdminWrap = $('#cb-admin-wrap'),
		cbReviewSwitch = $('#cb-review-onoff'),
		cbReviewWrap = $('#cb-review-wrap'),
		cbReviewAddCrit = $('#cb-add-crit'),
		cbReviewAddPro = $('#cb-add-pro'),
		cbReviewAddCon = $('#cb-add-con'),
		cbReviewAddAff = $('#cb-add-aff'),
		cbCrits = $( "#cb-criterias" ),
		cbAffButtons = $('#cb-aff-buttons'),
		cbPros = $( "#cb-pros" ),
		cbCons = $( "#cb-cons" ),
		cbSliders = $( ".cb-review-slider" ),
		cbGalleryImgCont = $('#cb-gallery-cont'),
		cbFinalScoreInput = $('#cb-final-score'),
		cbFinalScoreInput100 = $('#cb-final-score-100'),
		cbFinalScoreInputOverride = $('#cb-final-score-override'),
		cbGalleryImgContInput = cbGalleryImgCont.find('> input'),
		cbgalleryCounter = 0,
		cbSliderCounter = 0,
		cbSliderMax = cbCrits.data('cb-max'),
		cbSliderFormat = cbCrits.data('cb-format'),
		cbSliderStep = cbCrits.data('cb-step'),
		cbCP = $('.lets-review-colorpicker'),
		cbCustomImgEl = $('#cb-custom-image-wrap'),
		cbCounter, cbThis, cbTabData, cbDataTrig, cbVar, cbAdminData, cbParentParent, cbSliderFormatCache, cbTotal, cbX, cbY, cbParent, cbData, cbSliderValue, cbGalleryImgs, cbImgUrl, cbOb, cbCritScores, cbMediaModal, cbSingleMediaModal, cbSingleImg, cbSingleImgD, cbSingleImgDInput, cbSingleImgName, cbColor, cbRadioEl;

	cbAdminTabsA.click( function(e) {
		e.preventDefault();
		cbThis = $(this);
		cbParent = cbThis.parent();

        if ( cbParent.hasClass('nav-tab-active') ) {

        	return;

        } else {

        	cbTabData = cbThis.data( 'cb-href' );
        	
        	cbAdminTabs.find('.nav-tab-active').removeClass('nav-tab-active');
        	cbTabContentChildren.hide();
        	cbParent.addClass('nav-tab-active');
        	$('#' + cbTabData).show();

        }

    });

   var cbGDCheck = function( cbThis, cbType, cbVisiblity ) {

   		if ( cbDebug === true ) {
   			console.log('cbGDCheck');
   		}  		
   		
   		cbThis.each( function() {
   			cbRadioEl = $(this).closest('.cb-radio-images-element');
   			if ( ( cbType === 'load' ) && ( ! cbRadioEl.find('input').is(':checked') ) ) {
	   			return;
	   		}

			cbDataTrig = cbRadioEl.data('cb-trigger');

			cbAdminData = $(this).closest('.cb-trigger-wrap').data('cb-trigger');

			if ( typeof cbAdminData !== 'undefined' ) {

				if ( cbAdminData.indexOf(' ') >= 0 ){

					cbAdminData = '.' + cbAdminData.replace( ' ', ', .');
					cbVar = cbAdminWrap.find( cbAdminData );
					cbParent = cbVar.closest('tr');

				} else {
					cbVar = cbAdminWrap.find('.' + cbAdminData);

					cbParent = cbVar.closest('tr');
				}


				if ( cbDataTrig === 'cb-trig' ) {
					cbParent.addClass( cbVisiblity );
				} else {
					cbParent.removeClass( cbVisiblity );
				}	

				if ( cbRadioEl.find('input').val() == 5 ) {
	   				cbCustomImgEl.closest('tr').addClass( cbVisiblity );
	   			} else {
	   				cbCustomImgEl.closest('tr').removeClass( cbVisiblity );
	   			}
			}
		});
   }

   	cbAdminWrap.on('click', '.cb-review-design > .cb-radio-images-element img', function( e ){
		cbGDCheck( $(this), 'click', 'cb-hidden-gd' );

	});

   	cbAdminWrap.on('click', '.cb-review-format > .cb-radio-images-element img', function( e ){
		cbGDCheck( $(this), 'click', 'cb-show-tr' );

	});

	$('#cb-custom-icon').add('#cb-custom-image-wrap').closest('tr').addClass('cb-hidden');


   cbGDCheck( cbAdminWrap.find('.cb-review-design > .cb-radio-images-element img'), 'load', 'cb-hidden-gd' );
   cbGDCheck( cbAdminWrap.find('.cb-review-format > .cb-radio-images-element img'), 'load', 'cb-show-tr', 5 );

	cbReviewSwitch.onoff();

	cbDoc.ready(function() {
		cbCP.each( function() {
			cbColor = $(this).prev();
			$(this).farbtastic( cbColor );
		});

  	});

	cbReviewSwitch.click( function() {
		if ( cbDebug === true ) {
			console.log('cbReviewSwitch Click');
		}
		if ( $(this).is(':checked') ) {
		    cbReviewWrap.slideDown('fast'); 
		} else {
		    cbReviewWrap.slideUp('fast');
		}
	});

	cbSliders.each( function() {

		cbSliderValue = $(this).next().attr('value');

		$(this).slider({
			min: 0,
			max: cbSliderMax,
			range: 'min',
			value: cbSliderValue,
			step: cbSliderStep,
		});

		if ( ! $('#lets-review-metabox').length ) {
			$(this).slider( "disable" );
		}
	});

	$('#wpbody .cb-main-wrap').on('click', '.cb-trigger-wrap > .cb-radio-images-element', function( e ){
		if ( cbDebug === true ) {
			console.log('#wpbody .cb-main-wrap on click, cb-radio-images-element');
		}
		cbThis = $(this);
		cbParent = cbThis.parent();
		cbData = cbThis.data('cb-trigger');
		
		if ( typeof cbData !== 'undefined' ) {
			cbVar = $('#' + cbData);
			if ( ! cbVar.hasClass('cb-show')  ) {
				cbParent.find('.cb-trigger-block').addClass('cb-hidden');
				cbParent.children().removeClass('cb-show');
				cbVar.addClass('cb-show');
			}
			
		} else {
			cbParent.children().removeClass('cb-show');
			cbParent.find('.cb-trigger-block').addClass('cb-hidden');
		}
	});

	$('#wpbody .cb-main-wrap').on('click', '.cb-trigger-wrap-slider > .cb-radio-images-element > label > img', function( e ){
		if ( cbDebug === true ) {
			console.log('#wpbody .cb-main-wrap on click, cbFinalScoreInput');
		}
		cbY = $(this);
		cbX = cbY.prev().val();

		cbSliderMax = 5;
		cbSliderStep = 0.1;
		cbSliderFormatCache = cbCrits.data('cb-format');
		cbCrits.data('cb-format', 'cb-format-' + cbX );
		cbSliderFormat = cbCrits.data('cb-format');

		if ( typeof cbSliderFormat === 'undefined' ) {
			return;
		}

		if ( cbX === '1' ) {
			cbSliderMax = 100;
			cbSliderStep = 1;
		} else if ( cbX === '2'  ) {
			cbSliderMax = 10;
		} 

		cbCrits.find('.cb-review-slider').each(function() {
			
			cbSliderValue = $(this).slider( 'option', 'value' );

			if ( cbSliderFormatCache.slice(-1) === '1' ) {

				if ( cbX === '2' ) {
					cbSliderValue = ( cbSliderValue / 10 );
				} else if  ( cbX !== '1' ) {
					cbSliderValue = Math.round( ( cbSliderValue / 20 ) * 10) / 10;
				}
				
			} else if ( cbSliderFormatCache.slice(-1) === '2' ) {
				if ( cbX === '1' ) {
					cbSliderValue = ( cbSliderValue * 10 );
				} else if  ( cbX !== '2' ) {
					cbSliderValue = Math.round( ( cbSliderValue / 2 ) * 10) / 10;
				}
				
			} else {
				if ( cbX === '1' ) {
					cbSliderValue = ( cbSliderValue * 20 );
				} else if ( cbX === '2' ) {
					cbSliderValue = ( cbSliderValue * 2 );
				}
				
			}
			$(this).next().val( cbSliderValue );

			$(this).slider( 'option', { max: cbSliderMax, step: cbSliderStep, value: cbSliderValue  } );
		});
		if ( cbSliderFormatCache.slice(-1) === '1' ) {

			cbFinalScoreInput100.val( cbFinalScoreInput.val() );
		} else if ( cbSliderFormatCache.slice(-1) === '2' ) {
			cbFinalScoreInput100.val( cbFinalScoreInput.val() * 10 );
		
		} else {
			cbFinalScoreInput100.val( cbFinalScoreInput.val() * 20 );
		}

		cbScoreCalc();
		
	});

	cbDoc.on( 'input', cbFinalScoreInput, function() {
		if ( cbDebug === true ) {
			console.log('on input, cbFinalScoreInput');
		}
		cbFinalScoreInput.prev().slider('value',  cbFinalScoreInput.val() );
	});


	cbDoc.on( 'slide', '.cb-review-slider', function( event, ui ) {
		if ( cbDebug === true ) {
			console.log('On Slide');
		}
		
		$(this).next().val( ui.value );
		if ( $(this).hasClass('cb-exclude') ) {

			cbFinalScoreInputOverride.val( cbFinalScoreInput.val() );
			cbSliderFormatCache = cbCrits.data('cb-format');
	    	if ( cbSliderFormatCache.slice(-1) === '1' ) {
				cbFinalScoreInput100.val( cbFinalScoreInput.val() );
			} else if ( cbSliderFormatCache.slice(-1) === '2' ) {
				cbFinalScoreInput100.val( cbFinalScoreInput.val() * 10 );
			
			} else {
				cbFinalScoreInput100.val( cbFinalScoreInput.val() * 20 );
			}
		} else {
			cbFinalScoreInputOverride.val('off');
			cbScoreCalc();
		}

	});

	function cbScoreCalc() {
		if ( cbDebug === true ) {
			console.log('cbScoreCal');
		}

		if ( typeof cbSliderFormat === 'undefined' ) {
			return;
		}

        var cbTempTotal = 0;

        cbCritScores = cbCrits.find('.cb-cri-score');


        cbCritScores.each(function() {
            cbTempTotal += parseFloat( $(this).val() );
        });

        if ( ( cbTempTotal === 0 ) && ( cbFinalScoreInput.val() > 0 ) ) {
        	return;
        }


        if ( cbSliderFormat.slice(-1) === '1' ) {
        	cbTotal = Math.round( cbTempTotal / cbCritScores.length );
        } else {
        	cbTotal = Math.round( cbTempTotal / cbCritScores.length * 10 ) / 10;
        }


        if ( isNaN(cbTotal) ) { 
        	cbFinalScoreInput100.val('');
        	cbFinalScoreInput.val('');
        	cbFinalScoreInput.prev().slider( 'option', { max: cbSliderMax, step: cbSliderStep, value: cbFinalScoreInput.val() } );
    	} else { 
    		if ( ( cbFinalScoreInputOverride.hasClass('cb-ldd') ) || ( cbFinalScoreInputOverride.val() === 'off' ) ) {
    			cbFinalScoreInput.val(cbTotal);
	    		cbFinalScoreInput.prev().slider( 'option', { max: cbSliderMax, step: cbSliderStep, value: cbTotal } );
	    		cbFinalScoreInputOverride.val('off');
    		}
	    	

	    	cbSliderFormatCache = cbCrits.data('cb-format');
	    	if ( cbSliderFormatCache.slice(-1) === '1' ) {
				cbFinalScoreInput100.val( cbFinalScoreInput.val() );
			} else if ( cbSliderFormatCache.slice(-1) === '2' ) {
				cbFinalScoreInput100.val( cbFinalScoreInput.val() * 10 );
			
			} else {
				cbFinalScoreInput100.val( cbFinalScoreInput.val() * 20 );
			}
	    }

    }

	cbCrits.add(cbPros).add(cbAffButtons).add(cbCons).add(cbGalleryImgCont).sortable();
	
	cbReviewAddCrit.click( function( e ) {
		e.preventDefault();
		
		cbCounter = $(this).data( 'cb-counter' );
		cbData = '<li id="cb-criteria-' + cbCounter + '" class="ui-state-default cb-list-field cb-criteria-field cb-clearfix"><div class="cb-criteria-title"><span class="cb-list-title">' + letsReview.cbTitle + '</span><input type="text" value="" id="cb-criteria-field-' + cbCounter + '" name="lets_review_criterias[' + cbCounter + '][title]"  class="cb-input"></div><div class="cb-criteria-score cb-clearfix"><span class="cb-list-title">' + letsReview.cbScoreTitle + '</span><div class="cb-review-slider cb-slider"></div><input type="text" class="cb-cri-score cb-input" value="0" name="lets_review_criterias[' + cbCounter + '][score]" readonly></div><a href="#" class="cb-button cb-remove"></a></li>'; 
		cbCrits.append( cbData );

		$('#cb-criteria-' + cbCounter).find('.cb-review-slider').slider({
			min: 0,
			range: 'min',
			max: cbSliderMax,
			step: cbSliderStep,
		});

		if ( ! $('#lets-review-metabox').length ) {
			$('#cb-criteria-' + cbCounter).find('.cb-review-slider').slider( "disable" );
		}

		$('#cb-criteria-field-' + cbCounter).focus();
		cbCounter++;
		cbScoreCalc();
		cbReviewAddCrit.data('cb-counter', cbCounter);
		
	});

	cbReviewAddAff.click( function( e ) {
		e.preventDefault();
		
		cbCounter = $(this).data( 'cb-counter' );
		cbData = '<li id="cb-aff-option-' + cbCounter + '" class="ui-state-default cb-list-field cb-affiliate-field cb-clearfix"><div class="cb-aff-option-title cb-list-hw"><span class="cb-list-title">' + letsReview.cbTitle + '</span><input type="text" value="" id="cb-aff-option-field-' + cbCounter + '" name="lets_review_aff_buttons[' + cbCounter + '][title]"  class="cb-input"></div><div class="cb-aff-option-url cb-list-hw cb-list-hw-2"><div class="cb-list-title">' + letsReview.cbUrlTitle + '</div><input type="text" value="" id="cb-aff-option-field-' + cbCounter + '" name="lets_review_aff_buttons[' + cbCounter + '][url]"  class="cb-input"></div><a href="#" class="cb-button cb-remove"></a></li>'; 
		cbAffButtons.append( cbData );
		$('#cb-aff-option-field-' + cbCounter).focus();
		cbCounter++;
		cbReviewAddAff.data('cb-counter', cbCounter);
		
	});

	cbReviewAddPro.click( function( e ) {
		e.preventDefault();
		
		cbCounter = $(this).data( 'cb-counter' );

		cbData = '<li id="cb-pro-' + cbCounter + '" class="ui-state-default cb-list-field"><div class="cb-pro-title"><span class="cb-list-title">' + letsReview.cbTitle + '</span><input type="text" value="" id="cb-pro-field-' + cbCounter + '" name="lets_review_pros[' + cbCounter + '][positive]"  class="cb-input"></div><a href="#" class="cb-button cb-remove"></a></li>'; 
		cbPros.append( cbData );
		$('#cb-pro-field-' + cbCounter).focus();
		cbCounter++;
		cbReviewAddPro.data('cb-counter', cbCounter);
		
	});

	cbReviewAddCon.click( function( e ) {
		e.preventDefault();
		
		cbCounter = $(this).data( 'cb-counter' );

		cbData = '<li id="cb-con-' + cbCounter + '" class="ui-state-default cb-list-field"><div class="cb-con-title"><span class="cb-list-title">' + letsReview.cbTitle + '</span><input type="text" value="" id="cb-con-field-' + cbCounter + '" name="lets_review_cons[' + cbCounter + '][negative]"  class="cb-input"></div><a href="#" class="cb-button cb-remove"></a></li>'; 
		cbCons.append( cbData );
		$('#cb-con-field-' + cbCounter).focus();
		cbCounter++;
		cbReviewAddCon.data('cb-counter', cbCounter);
		
	});

	$('#wpbody').on('click', '.cb-remove', function( e ){
		e.preventDefault();
		$(this).parent().remove();
		cbScoreCalc();
	});

	cbWindow.load(function() {
		cbScoreCalc();
		cbFinalScoreInputOverride.addClass('cb-ldd');
	});

	$('#wpbody').on('click', '.cb-single-image-trigger', function( e ){

	 	e.preventDefault();
	 	cbThis = $(this);
	 	cbSingleImgD = $(this).data( 'cb-dest' );
	 	cbSingleImgName = $(this).data( 'cb-name' );
	 	cbSingleImgDInput = $('#' + cbSingleImgD).find('> input');

	 	if ( typeof cbSingleMediaModal !== 'undefined' ) {

			cbSingleMediaModal.open();
			return;

		}

		cbSingleMediaModal = wp.media({
			title:  letsReview.cbMediaSTitle,
			button: {

				text: letsReview.cbMediaButton,
			},
			multiple: false
		});

	    cbSingleMediaModal.on( 'select', function(){

        	cbSingleImg = cbSingleMediaModal.state().get('selection').first().toJSON();

			if ( typeof cbSingleImg.sizes.thumbnail !== 'undefined' ) {
				cbImgUrl = cbSingleImg.sizes.thumbnail.url;
			} else {
				cbImgUrl = cbSingleImg.url;
			}

             $('#' + cbSingleImgD).find('.cb-gallery-img').remove();
             $('#' + cbSingleImgD).append( '<span id="cb-img-' + cbSingleImg.id + '" data-cb-id="' + cbSingleImg.id + '" class="cb-gallery-img"><a href="#" class="cb-remove"></a><img src="' + cbImgUrl + '" alt=""><input type="hidden" value="' + cbSingleImg.id  + '" name="' + cbSingleImgName + '"></span>' );
            cbSingleImgDInput.val( cbSingleImg.id );

		});

		cbSingleMediaModal.open();

	});

	 $('#wpbody').on('click', '.cb-gallery-trigger', function( e ){
	 	
	 	e.preventDefault();
	 	cbThis = $(this);
	 	cbCounter = $(this).data( 'cb-counter' );

	 	if ( typeof cbMediaModal !== 'undefined' ) {

			cbMediaModal.open();
			return;

		}

		cbMediaModal = wp.media({
			title: letsReview.cbMediaTitle,
			button: {
				text: letsReview.cbMediaButton
			},
			multiple: true
		});

	    cbMediaModal.on( 'select', function(){

        	cbGalleryImgs = cbMediaModal.state().get("selection").models;

			for ( cbgalleryCounter; cbgalleryCounter < cbGalleryImgs.length; cbgalleryCounter++ ) {

				cbOb = cbGalleryImgs[cbgalleryCounter].toJSON();
				cbCounter++;

				if ( typeof cbOb.sizes.thumbnail !== undefined ) {
					cbImgUrl = cbOb.sizes.thumbnail.url;
				} else {
					cbImgUrl = cbOb.url;
				}

	            cbGalleryImgCont.append( '<span id="cb-img-' + cbOb.id + '" data-cb-id="' + cbOb.id + '" class="cb-gallery-img"><a href="#" class="cb-remove"></a><img src="' + cbImgUrl + '" alt=""><input type="hidden" value="' + cbOb.id  + '" name="lets_review_gallery_imgs[' + cbCounter + '][attachment-id]"></span>' );
	            cbGalleryImgContInput.val(function(i,val) { 
				     return val + (!val ? '' : ', ') + cbOb.id;
				});
				
				cbThis.data('cb-counter', cbCounter);
	        }

	        cbgalleryCounter = 0;

		});

		cbMediaModal.open();

	 });

})( jQuery );