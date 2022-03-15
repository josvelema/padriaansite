<?php
include 'functions.php';
?>
<?= template_header('Science') ?>

<link rel="stylesheet" href="assets/css/contact.css">
<?= template_nav() ?>
<?php
// Output messages
$responses = [];
// Check if the form was submitted
if (isset($_POST['email'], $_POST['subject'], $_POST['name'], $_POST['msg'])) {
  // Validate email adress
  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $responses[] = 'Email is not valid!';
  }
  // Make sure the form fields are not empty
  if (empty($_POST['email']) || empty($_POST['subject']) || empty($_POST['name']) || empty($_POST['msg'])) {
    $responses[] = 'Please complete all fields!';
  }
  // If there are no errors
  if (!$responses) {
    // Where to send the mail? It should be your email address
    $to      = 'pieter@pieter-adriaans.com';
    // Send mail from which email address?
    $from = 'pieter@pieteradriaans.codette.net';
    // Mail subject
    $subject = $_POST['subject'];
    // Mail message
    $message = $_POST['msg'];
    // Mail headers
    $headers = 'From: ' . $from . "\r\n" . 'Reply-To: ' . $_POST['email'] . "\r\n" . 'X-Mailer: PHP/' . phpversion();
    // Try to send the mail
    if (mail($to, $subject, $message, $headers)) {
      // Success
      $responses[] = 'Message sent!';
    } else {
      // Fail
      $responses[] = 'Message could not be sent! Please check your mail server settings!';
    }
  }
}
?>

<header>
  <h1>Contact / Address</h1>

</header>
<main class="rj-home">
  <section class="rj-contact-section">

    <form class="contact" method="post" action="">
      <h2>Contact Form</h2>
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
      <?php if ($responses) : ?>
        <p class="responses"><?php echo implode('<br>', $responses); ?></p>
      <?php endif; ?>
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


<?= template_footer() ?>