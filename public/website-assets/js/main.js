$.fn.extend({
    treed: function (o) {

        var openedClass = 'fa-minus-square';
        var closedClass = 'fa-plus-square';

        if (typeof o != 'undefined') {
            if (typeof o.openedClass != 'undefined') {
                openedClass = o.openedClass;
            }
            if (typeof o.closedClass != 'undefined') {
                closedClass = o.closedClass;
            }
        }

        //initialize each of the top levels
        var tree = $(this);
        tree.addClass("tree");
        tree.find('li').has("ul li").each(function () {
            var branch = $(this); //li with children ul
            branch.prepend("<i class='indicator fa " + closedClass + "'></i>");
            branch.addClass('branch');
            branch.on('click', function (e) {
                if (this == e.target) {
                    var icon = $(this).children('i:first');
                    icon.toggleClass(openedClass + " " + closedClass);
                    $(this).children().children().slideToggle('slow');
                }
            })
            branch.children().children().slideToggle('slow');
        });
        //fire event from the dynamically added icon
        tree.find('.branch .indicator').each(function () {
            $(this).on('click', function () {
                $(this).closest('li').click();
            });
        });
        //fire event to open branch if the li contains an anchor instead of text
        tree.find('.branch>a').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li a').click();
                e.preventDefault();
            });
        });
        //fire event to open branch if the li contains a button instead of text
        tree.find('.branch>button').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li a').click();
                e.preventDefault();
            });
        });
    }
});
$('#tree1,#category_tree').treed();
function cart_qty_plus(rowId) {
    var quantity = parseInt($('#product_quantity_' + rowId).val());
    $('#product_quantity_' + rowId).val(quantity + 1);
    var new_qty = $('#product_quantity_' + rowId).val();
    update_cart(rowId,new_qty);
}
function cart_qty_minus(rowId) {
    var quantity = parseInt($('#product_quantity_' + rowId).val());
    if (quantity > 1) {
        $('#product_quantity_' + rowId).val(quantity - 1);
    }
    var new_qty = $('#product_quantity_' + rowId).val();
    if (new_qty >= 1) {
        update_cart(rowId,new_qty);
    }

}


jQuery(function($) {
    "use strict";
  
        /* ----------------------------------------------------------- */
      /*  Preload
      /* ----------------------------------------------------------- */
  
     

      /* ----------------------------------------------------------- */
      /*  Back to top
      /* ----------------------------------------------------------- */
  
          $(window).scroll(function () {
              if ($(this).scrollTop() > 50) {
                   $('#back-to-top').fadeIn();
              } else {
                   $('#back-to-top').fadeOut();
              }
          });
  
          // scroll body to 0px on click
          $('#back-to-top').on('click', function () {
               $('#back-to-top').tooltip('hide');
               $('body,html').animate({
                    scrollTop: 0
               }, 800);
               return false;
          });
          
          $('#back-to-top').tooltip('hide');
  
          
          /* Preloade */
  
          
  
  
});
