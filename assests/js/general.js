$(document).ready(function () {

  var currentPageNumber = 1;
  $(".saltopagina").each(function () {
    currentPageNumber++
    $(this).attr('id', 'anchor' + currentPageNumber);
  });

  $("body>section h1, body>section h2")
  .not(".noindex, section:last-of-type *")
  .each(function () {
    var label;
    var pageNumber = 0;
    if($(this).prop("tagName") == 'H1') {
      label = $(this).children("strong").text();
    } else label = this.innerHTML.replace(/(<([^>]+)>)/gi, "");

    $.each(indice, function(i, item) {
      if (label == item.title) {
        pageNumber = item.page;
        indice[i].title = "";
      }
    });
  
    $("body>section:nth-of-type(3) div").append('<a href="#anchor' + pageNumber + '" class="like' + $(this).prop("tagName") + '"><span>'+pageNumber+'</span>' + label + '</a>');
  });

  $("body>section:nth-of-type(3) div a").on('click', function (e) {
    e.preventDefault();
    link = $(this).attr('href').substring(1);
    $(".saltopagina").each(function () {
      if($(this).attr('id') == link) {

        $('html, body').animate({
          scrollTop: $(this).offset().top
        }, 500);

      }
    });

  });

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