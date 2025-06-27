
  <section class="faq-section">
        <div class="container">
          <h2><img src="../img/help.png">Frequently Asked Questions</h2>
          <div class="faq">
              <div class="faq-item hidden">
              <h3>Wat zijn de toelatingseisen voor de opleiding Astronomie?<span class="arrow">&rarr;</span></h3>
              <p>De toelatingseisen voor de opleiding Astronomie zijn een bachelordiploma in de natuurkunde of een verwant vakgebied.</p>
              </div>
              <div class="faq-item hidden">
              <h3>Welke vakken zijn opgenomen in de opleiding Astronomie?<span class="arrow">&rarr;</span></h3>
              <p>De opleiding Astronomie omvat vakken zoals Sterrenkunde, Galactische Astronomie en Kosmologie.</p>
              </div>
              <div class="faq-item hidden">
              <h3>Welke carrièremogelijkheden zijn er voor afgestudeerden in Astronomie?<span class="arrow">&rarr;</span></h3>
              <p>Afgestudeerden in Astronomie kunnen carrières nastreven als onderzoekswetenschappers, observatoriumtechnici of wetenschapsdocenten.</p>
              </div>
              <div class="faq-item hidden">
              <h3>Zijn er financiële ondersteuningsmogelijkheden beschikbaar voor studenten Astronomie?<span class="arrow">&rarr;</span></h3>
              <p>Ja, er zijn beurzen en subsidies beschikbaar voor studenten Astronomie. Je kunt contact opnemen met het financiële ondersteuningsbureau van de universiteit voor meer informatie.</p>
              </div>
              <div class="faq-item hidden">
              <h3>Kan ik tijdens de opleiding Astronomie stage lopen?<span class="arrow">&rarr;</span></h3>
              <p>Ja, de opleiding Astronomie biedt mogelijkheden voor stages bij observatoria en onderzoeksinstituten.</p>
        </div>
      </section>


    <style>
        .faq-item {
          margin-bottom: 20px;
        }

        .faq-item h3 {
          cursor: pointer;
          margin: 0;
          padding: 10px;
          background-color: #f1f1f1;
          border: 1px solid #ccc;
          border-radius: 8px;
          font-size: 28px;
          font-weight: bold;
        }

        .faq-item p {
          margin: 10px 0;
          font-size: 26px;
          line-height: 1.5;
          display: none;
        }

        .arrow {
          float: right;
        }
    </style>

     
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script>
        $(document).ready(function(){
          $(".faq-item h3").on("click", function(){
            $(".faq-item p").slideUp();
            $(".faq-item .arrow").html('&rarr;');
        
            var content = $(this).next('p');
            var arrow = $(this).find('.arrow');
            if (content.is(":visible")) {
              content.slideUp();
              arrow.html('&rarr;');
            } else {
              content.slideDown();
              arrow.html('&#9733;');
            }
          });
        });
        </script>