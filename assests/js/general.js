$(document).ready(function () {


  //WordCount
  var cont = jQuery("body").html();
  cont = cont.replace(/<[^>]*>/g," ");
  cont = cont.replace(/\s+/g, ' ');
  cont = cont.trim();
  var n = cont.split(" ").length
  console.log(n+" words");

  //PageCount
  console.log(($('.saltopagina').length + 1)+" pages");

});