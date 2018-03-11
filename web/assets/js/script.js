$(document).ready(function() {


  /* JS SIDEBAR AND HAMBURGER MENU */

  var trigger = $('.hamburger'),
    overlay = $('.overlay'),
    isClosed = false;

  trigger.click(function() {
    hamburger_cross();
  });

  function hamburger_cross() {

    if (isClosed == true) {
      overlay.hide();
      trigger.removeClass('is-open');
      trigger.addClass('is-closed');
      isClosed = false;
    } else {
      overlay.show();
      trigger.removeClass('is-closed');
      trigger.addClass('is-open');
      isClosed = true;
    }
  }

  $('[data-toggle="offcanvas"]').click(function() {
    $('#wrapper').toggleClass('toggled');
  });


  /* delete windows avert */
  var deleteLinks = document.querySelectorAll('.delete');

  for (var i = 0; i < deleteLinks.length; i++) {
    deleteLinks[i].addEventListener('click', function(event) {
      event.preventDefault();

      var choice = confirm(this.getAttribute('data-confirm'));

      if (choice) {
        window.location.href = this.getAttribute('href');
      }
    });
  }

  /* OBS COMMENT DISPLAY INTERFACE */

  var obsCommentContentElt = $("#obsCommentContent");
  var obsCommentBadgeElt = $("#obsCommentBadge");
  var obsCommentButtonDisplayElt = $("#obsCommentButton");
  var obsCommentButtonElt = document.getElementById("obsCommentButton");
  var display = false;

  $("#obsCommentButton").click(function() {
    obsComment_display();
  });

  function obsComment_display() {

    if (display == false) {
      display = true;
      obsCommentContentElt.show();
      obsCommentBadgeElt.show();
      obsCommentButtonElt.innerHTML = 'Cacher le Commentaire  <i class="fa fa-eye-slash"></i>';
    } else {
      display = false;
      obsCommentContentElt.hide();
      obsCommentBadgeElt.hide();
      obsCommentButtonElt.innerHTML = 'Voir le Commentaire  <i class="fa fa-eye"></i>';
    }
  }

  $(window).resize(function() {

    screenSize = $(window).width();

    if (screenSize >= 750) {
      obsCommentContentElt.show();
      obsCommentBadgeElt.show();
    } else {
      obsCommentContentElt.hide();
      obsCommentBadgeElt.hide();
    }
  });


  /* JS PROFILE FILTER PANEL OPTION */

  $('.star').on('click', function() {
    $(this).toggleClass('star-checked');
  });

  $('.ckbox label').on('click', function() {
    $(this).parents('tr').toggleClass('selected');
  });

  $('.btn-filter').on('click', function() {
    var $target = $(this).data('target');
    if ($target != 'all') {
      $('.table tr').css('display', 'none');
      $('.table tr[data-status="' + $target + '"]').fadeIn('slow');
    } else {
      $('.table tr').css('display', 'none').fadeIn('slow');
    }
  });

});

/* JS CIRCULAR MENU */

$(document).ready(function(ev) {
  var toggle = $('#ss_toggle');
  var menu = $('#ss_menu');
  var rot;

  $('#ss_toggle').on('click', function(ev) {
    rot = parseInt($(this).data('rot')) - 180;
    menu.css('transform', 'rotate(' + rot + 'deg)');
    menu.css('webkitTransform', 'rotate(' + rot + 'deg)');
    if ((rot / 180) % 2 == 0) {
      //Moving in
      toggle.parent().addClass('ss_active');
      toggle.addClass('close');
    } else {
      //Moving Out
      toggle.parent().removeClass('ss_active');
      toggle.removeClass('close');
    }
    $(this).data('rot', rot);
  });

  menu.on('transitionend webkitTransitionEnd oTransitionEnd', function() {
    if ((rot / 180) % 2 == 0) {
      $('#ss_menu div a').addClass('ss_animate');
    } else {
      $('#ss_menu div a').removeClass('ss_animate');
    }
  });

});
