$(document).on('click', ".clickable", function () {
 $(this).toggleClass('table-success').siblings().removeClass("table-success"); 
});

$(function() {
  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    // save the latest tab:
    localStorage.setItem('lastTab', $(this).attr('data-target'));
  });
  // go to the latest tab, if it exists:
  var lastTab = localStorage.getItem('lastTab');
  if (lastTab) {
    $('[data-target="' + lastTab + '"]').tab('show');
  }
});
 $("#clear").click(function(){
    $("input[name=search]").val('');
});

$(document).ready(function() {
    $(".alert-dismissible").fadeTo(2000, 300).slideUp(300, function() {
      $(".alert-dismissible").slideUp(300);
  });
});
