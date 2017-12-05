$(document).ready(function(){
  // Reset Font Size
  var originalFontSize = $('.content').css('font-size');
    $("#resetFont").click(function(){
    $('.content').css('font-size', originalFontSize);
  });
  // Increase Font Size
  $("#increaseFont").click(function(){
    var currentFontSize = $('.content').css('font-size');
    var currentFontSizeNum = parseFloat(currentFontSize, 10);
    var newFontSize = currentFontSizeNum*1.05;
    $('.content').css('font-size', newFontSize);
    return false;
  });
  // Decrease Font Size
  $("#decreaseFont").click(function(){
    var currentFontSize = $('.content').css('font-size');
    var currentFontSizeNum = parseFloat(currentFontSize, 10);
    var newFontSize = currentFontSizeNum*0.95;
    $('.content').css('font-size', newFontSize);
    return false;
  });
  
  $("#print").click(function(){
      window.print();
  });
});