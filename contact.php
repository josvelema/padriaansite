<?php
include 'functions.php';
?>
<?= template_header('Contact') ?>

<link rel="stylesheet" href="assets/css/contact.css">
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?= template_nav() ?>

<header>
  <h1>Contact / Address</h1>

</header>
<main class="rj-home">
  <section class="rj-contact-section">

    <form class="contact" method="post" id="contact-form">
      <div class="fields">
        <label for="email">
          <i class="fas fa-envelope"></i>
          <input id="email" type="email" name="email" placeholder="Your Email" required>
        

        <label for="name">
          <i class="fas fa-user"></i>
          <input type="text" name="name" placeholder="Your Name" required>
        </label>
 
        <input type="text" name="subject" placeholder="Subject" required>
          <textarea name="msg" placeholder="Message" required></textarea>
      </div>
 
      <div class="g-recaptcha" data-sitekey="6LfdSrglAAAAAEWiISY3wc6FZWDizXDcTSXp_S1p"></div>
      <div class="response">

</div>
      <input type="submit" class="rj-button rj-contact-submit">
    </form>
    <article class="rj-about rj-contact-address">
      <h2>Addresses of Ateliers</h2>
      <p>Work of Pieter and Rini Adriaans can be seen at their studio<br>
        <a title="Atelier de Kaasfabriek" href="https://www.facebook.com/pg/AtelierdeKaasfabriek/">Atelier de Kaasfabriek</a> on the island S&atilde;o Jorge at the Azores:
      </p>

      <div class="rj-contact-address-card">
        <img src="assets/img/kaasfabriekgebouw.jpeg" alt="Atelier de Kaasfabriek" width="250" height="188" />
        <p><em>Atelier de Kaasfabriek</em></p><br />
          <p>Santo Antonio , S&atilde;o Jorge<br />A&ccedil;ores, Portugal</p>
          
          <a href="https://www.facebook.com/AtelierdeKaasfabriek" target="_blank" class="rj-social-item"><i class="fa-brands fa-facebook"></i>Atelier de Kaasfabriek</a>
        </p>
      </div>

      <br />
      <div class="rj-contact-address-card">

        <img title="Whale tail gate Manadas" src="assets/img/adriaanshuis.jpeg" alt="Whale tail gate at the house in Manadas" width="228" height="171" />
        <p><em>Atelier Adriaans A&ccedil;ores<br></em></p>
        Cais das Manadas <br />Manadas, S&atilde;o Jorge<br />A&ccedil;ores, Portugal<br />tel. 295414330
        </p>
      </div>


    </article>
  </section>

</main>

<script>
    document.getElementById('contact-form').addEventListener('submit', async (e) => {
      console.log('submitting form');
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);

        const responseDiv = document.querySelector('.response');
        responseDiv.innerHTML = '';

        try {
            const response = await fetch('contactform.php', {
                method: 'POST',
                body: formData,
            });
            const responseText = await response.text(); // Get the response text
            console.log(responseText); // Log the response text
            const result = JSON.parse(responseText); // Parse the response text as JSON

            if (result.success) {
              console.log('success');
                responseDiv.innerHTML = result.success;
                form.reset();
                grecaptcha.reset();
            } else if (result.errors) {
              responseDiv.innerHTML = '';
              result.errors.forEach(error => {
                  const errorParagraph = document.createElement('p');
                  errorParagraph.textContent = error;
                  responseDiv.appendChild(errorParagraph);
              });
                console.log(result.errors);
            } else {
                responseDiv.innerHTML = 'An unexpected error occurred.';
                console.log(result);
            }
        } catch (err) {
            console.error(err);
            responseDiv.innerHTML = 'An error occurred while submitting the form. Please try again.';
        }
    });
</script>



<?= template_footer() ?>