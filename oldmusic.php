<?php
include 'functions.php';
?>
<?= template_header('Music') ?>
<?= template_header_other() ?>
<?= template_nav('music') ?>

<main class="rj-black-bg-main">
  <header class="rj-music">
    <h1>Music</h1>
    <img src="assets/img/musicbanner.jpg" alt="Music banner">
    <!-- <div class="rj-science-img">
      </div> -->
  </header>
  <section>

    <aside class="rj-main-menu">
      <h3>Menu</h3>
      <ul>
        <li><a href="#" title="BasAlt" onclick="show('basalt')">BasAlt</a></li>
        <li><a href="#" title="Azorean Suite" onclick="show('suite')">Azorean Suite</a></li>
        <li><a href="#" title="Blues op Klompen - CD" onclick="show('klompen')">Blues op Klompen - CD</a></li>
        <li><a href="#" title="The Pacer CD" onclick="show('pacer')">The Pacer CD</a></li>
        <li><a href="#" title="Rare songs" onclick="show('rare')">Rare songs</a></li>
        <li><a href="#" title="Computer music" onclick="show('computer')">Computer music</a></li>
        <li><a href="#" title="The Viola da Terra" onclick="show('viola')">The Viola da Terra</a></li>
      </ul>
    </aside>


    <article id="musichome">
      <div class="rj-flex">
        <p>
          <img src="assets/img/songs-from-an-island-sleeve-front.jpg" alt="Front Cover CD Songs from an Island" width="158" height="159" data-id="music">
          <img src="assets/img/blues-op-klompen-sleeve-front.jpg" alt="Blues op Klompen Cover CD" width="160" height="160" data-id="music"> &nbsp; &nbsp;&nbsp;&nbsp;
          <img src="assets/img/pacer-cover.jpg" alt="Pacer Cover CD" width="167" height="161" data-id="music">&nbsp;&nbsp;&nbsp;&nbsp;
        </p>
      </div>
    </article>

    <article id="basalt" style="display: none">

      <h1>BasAlt&nbsp;</h1>
      <p>BasAlt brings you sunny music from the island of <a title="Sao Jorge" href="https://en.wikipedia.org/wiki/S&atilde;o_Jorge_Island">S&atilde;o Jorge</a>, one of the <a title="Azoren" href="https://en.wikipedia.org/wiki/Azores">Azores</a>,&nbsp;in the middle of the Atlantic Ocean. Pieter Adriaans (Bass) and Joana van Olphen (Alt) write almost all of their own material: nice melodic songs in English, Dutch and Portuguese about the simple things of everyday life....&nbsp;
        <span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;<br /></span><span>In 2012 they released their first CD, Songs from an Island, and they are currenty writing material for a new project....&nbsp;</span>
      </p>
      <p><iframe src="http://www.youtube.com/embed/HjiyOzXKhXw" frameborder="0" width="292" height="241"></iframe>
        &nbsp; &nbsp; &nbsp;&nbsp;<iframe src="http://www.youtube.com/embed/7j6WBsh4Uzw" frameborder="0" width="291" height="241"></iframe>
      </p>
      <p><img style="font-size: 1.2em;" src="assets/img/songs-from-an-island-sleeve-front.jpg" alt="Front Cover CD Songs from an Island" width="292" height="293" />&nbsp; &nbsp; &nbsp; &nbsp;<img style="font-size: 1.2em;" src="assets/img/songs-from-an-island-sleeve-back.jpg" alt="Back Cover CD Songs from an Island" width="290" height="292" /><span>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span>
      </p>
    </article>

    <article id="suite" style="display: none">

      <h3>Azorean Suite</h3>
      <p>Read more about this composition in&nbsp;
        <a href="#moreinfo" onclick="suiteShow('moreinfo')">the &nbsp;story behind the Azorean Suite</a>
      </p>
      <p>The Azorean Suite consists of five parts: </p>

      <ul>
        <li><a href="#madrugada" title="" onclick="suiteShow('madrugada')">Madrugada</a></li>
        <li><a href="#tempestade" title="" onclick="suiteShow('tempestade')">Tempestade</a></li>
        <li><a href="#burlesque" title="" onclick="suiteShow('burlesque')">Burlesque</a></li>
        <li><a href="#chamarrita" title="" onclick="suiteShow('chamarrita')">Chamarrita</a></li>
        <li><a href="#tourada" title="" onclick="suiteShow('tourada')">Tourada</a></li>
      </ul>
      <article id="suiteMain">
        <p><img style="float: right;" title="Renato Bettencourt" src="assets/img/105005388420061858396365973692696289217217n.jpg" alt="Renato Bettencourt" width="150" height="150" />A suite of five pieces for classical orchestra Inspired by the Azores and composed by Pieter Adriaans.
          The world premiere will be by the <a href="http://www.vsso.nl">Vechtstreek Symphonie Orkest</a>&nbsp;on september the 27th 2014 in theatre 4en 1 in Breukelen (20.15 h.). Guest of honour is Renato Bettencourt, a player of the
          <a href="/music/the-viola-da-terra.html">Viola da Terra </a>from the island of S&atilde;o&nbsp;Jorge. With Greetje Falkenhagen reciting portuguese poems (by Carlos Faria and Vittorino Nem&eacute;sio)&nbsp;and the dance group
          <a href="http://www.home.zonnet.nl/aslavradeiras/">'As lavradeiras</a>'. Tickets can be ordered at:&nbsp;<a href="mailto:penningmeester@vsso.nl">penningmeester@vsso.nl</a>.&nbsp;
        </p>
      </article>

      <article id="madrugada" style="display: none">
        <h3>Madrugada&nbsp;</h3>
        <p>(A major, 2/4, tempo 90, 162 measures, 3 min 40 sec.)</p>
        <p>A sort of ouverture. The inspiration for the piece is the magnificent sunrises we experience here every morning at the Port of Manadas. The piece started as a guitar improvisation. The main theme is based on a harmonization of a chromatically descending ladder: A major 7, A minor 7, B minor 7, D minor, A major 7. From measure 57 there is a hint of a slow Bossa Nova movement.&nbsp;</p>

      </article>

      <article id="tempestade" style="display: none">
        <h3>Tempestade</h3>
        <p>(A major, 4/4, tempo 100, 154 measures, 6 min 10 sec.)</p>
        <p>The storm piece. It opens with a melodic improvisation on A minor, D minor E7 chords that was originally written for guitar. The next parts were developed on the piano. We continue with stacked arpeggios of the A minor chord that change into a strongly syncopated melody played in march tempo on a descending sequence A minor, G, F, E7. The piece dies out with A minor arpeggios again.&nbsp;</p>


      </article>

      <article id="burlesque" style="display: none">
        <h3>Burlesque</h3>
        <p>(Atonal, 4/4, tempo 120, 48 measures, 1 min. 40 sec.)</p>
        <p>Also originally a guitar improvisation. A leading thought in the composition was the way life reclaims its normal pace after a storm. Little creatures come out in the open on the rocks, birds sing in the trees. Cagarros sheer over the waves. The piece also tries to invoke the atmosphere of quiet magic that is so typical for our bay when the sea calms down. Technically the piece explores the possibilities of diminished and whole tone scales. The opening sequence of stacked tritones and descending thirds is used as finger practice by base players, I recently discovered.&nbsp;</p>
      </article>

      <article id="chamarrita" style="display: none">
        <h3>Chamarrita</h3>
        <p>(G major, 3/4, tempo 140, 146 measures, 2 min 20.)</p>
        <p>A chamarrita is a fast local Azorean dance. It probably originated from the polkas and folk dances of Flemish immigrants but the fast polyrhythm also hints at African roots. Every village used to have its own chamarritas and older musicians still remember them. According to some musicologists (Renato Almeida) the chamarrita was transported to Argentine by Azorean immigrants, where it was one of the defining influences in the development of the Milonga and later the Tango. I learned the melody of this &ldquo;Chamarrita de Sao Jorge&rdquo; from my gardener Francisco Silveira, who is a keen mandolin player. He thinks the melody came originally from the island of Pico. It is a so-called chamarrita de baixo, because it is played high (in Portuguese terms low) on the neck of the Viola da Terra, a local guitar-like instrument. He also gave me copies of transcriptions for voice and piano of traditional songs by the well known Portuguese composer and musicologist Francisco de Lacerda (1869-1934) who was born on Sao Jorge. These helped me to arrange the melody.&nbsp;</p>
      </article>
      <article id="tourada" style="display: none">
        <h3>Tourada</h3>
        <p>(E minor, 12/8, tempo 130, 40 measures, 1 min 20 sec. )</p>
        <p>As suites go this one is ending with a light jig in 12/8 beat. It is based on a fast guitar improvisation&nbsp; that I have developed and refined over the years. A tourada a corda is a traditional event where a bull is let loose in the village, held under control by a long rope. In general it is big fun and fairly harmless, but there is an interesting underlying tension. It reminds me very much of old customs and fertility rites around the Mediterranean, where life and death are tightly connected and that go back to antiquity and beyond.&nbsp;&nbsp;</p>
      </article>

      <article id="moreinfo" style="display: none">
        <h3>More about the Azorean Suite</h3>
        <p>The Azorean Suite grew out of a piano improvisation that I have often played for friends in the last fifteen years. The piece has an unusual dramatic impact and it has been a favorite request piece at parties for a long time. When playing I always thought of a storm. The wind slowly building up. In the centre an inferno of wind, lightning and rain and then the wind slowly dying out. In my mind I could also hear a full orchestration of the piece played by a classical orchestra.</p>
        <p>About ten years ago we partly moved to the Azores and living there in winter we had our fair share of storms. Azorean nature is of a breathtaking beauty and the landscape is a constant source of inspiration. In the course of time we did a lot of music projects on the Azores. Recently we released the CD &ldquo;Songs of an Island&rdquo; with BasAlt.&nbsp; Gradually the idea of composing a group of works around the storm theme as an homage to the Azores emerged. The well known Fingal's Cave Ouverture by Mendelssohn was also on my mind when I sailed around the Hebrides in 2007. Originally I had a much more ambitious plan for an animation movie about a fisherman going to sea on a beautiful day and getting caught in a unexpected storm, being swept from his boat by watergods, like the Nix, the Klaboterman, diabretes, sirens and finally rescued by nymphs, nereides or what have you. The music, then would be the score of the movie. &nbsp;I put this idea on my large stack of unrealizable projects and forgot about it.</p>
        <p>A couple of years ago I was interviewed on local radio about another music project &ldquo;Blues op Klompen&rdquo; (Blues on wooden shoes) when I made a casual remark about the fact that I had ideas for a work for classical orchestra called &ldquo;the Azorean Suite&rdquo;. To my surprise, after broadcast, I was contacted by Hans Hering. He was chairman of amateur orchestra the &ldquo;VechtStreek Symphonie Orkest&rdquo; (VSSO). The year 2012 was a lustrum year and they would very much like to have the world premiere of an original work. Would I be interested? I was reasonably flabbergasted by the instant success of my &lsquo;bluff your way in to composing&rsquo; hubris. What to do? After some deliberation I decided to accept the challenge. In the autumn of 2011 I sent the board of the VSSO a rough plan for the work. The reaction I got was benign and encouraging. I selected software and spent a lot of time learning to work with Sibelius. In January 2012 I composed the first part &ldquo;Madrugada&rdquo; in my house in Manadas on Sao Jorge. In the spring of 2012 I fell ill and was incapable to do any work for a long time. I put the plans on the shelf and wrote Hans a note that I could not stick to any deadlines in 2012. I thought the project was dead. I will always be grateful for his supportive reaction: I could take my time and yes they were still interested.</p>
        <p>By the time I was feeling quite miserable and my wife Rini and I decided to spend the whole summer in Manadas. Here a small miracle happened. My health gradually improved on a program of long walks and much rest. After a while I started to work again. In short, it has been one of my most productive summers ever. I wrote some songs, finished several papers, some paintings and a book on painting. &ldquo;The summer would be perfect if I also would have finished the Azorean Suite&rdquo; I said to Rini when our stay was coming to an end.&nbsp; Inspiration is a weird thing. You must grab it when it&rsquo;s there. When it&rsquo;s gone it may never come back. We decided to extend our stay with several weeks to give me time to finish the Suite. The last weeks I have been working constantly on the composition, up to sixteen hours a day, getting sometimes only a couple of hours sleep per night.&nbsp; The work is ready in concept. The rest is downhill skiing. At the time of writing I do not know whether the work will ever be executed but I&rsquo;m immensely happy with the result. I hope to work with the people of VSSO to realize a live performance in the near future but for me, as a composer, the work is already there. The scores are there. I can make wav-files in Sibelius and share them with other people. That is all a composer can wish for. &nbsp;</p>
        <p>Manadas September 2012.&nbsp;</p>
      </article>


      <!-- 
      <h3><a href="/music/azorean-suite/madrugada.html">Madrugada</a>&nbsp;</h3>
      <p><img title="Poster Suite" src="assets/img/poster-suite-english.jpg" alt="Poster Suite" width="500" height="647" /><a href="/music/azorean-suite/madrugada.html">Info</a>
        <span> about Madrudaga. Listen to the&nbsp;</span>
        <a href="/data/upload/files/madrugada-def.mp3">Mp3 Midi version</a>
        <span of Madrugada</span>
      </p>
     <h3><a href="/music/azorean-suite/tempestade.html">Tempestade</a></h3>
      <p><a href="/music/azorean-suite/tempestade.html">Info</a> About Tempestade. Listen to the&nbsp;
        <a href="/data/upload/files/tempestade-def.mp3">Mp3 Midi Version</a>&nbsp;of Tempestade
      </p>
      <h3><a href="/music/azorean-suite/burlesque.html">Burlesque</a>&nbsp;</h3>
      <p><a href="/music/azorean-suite/burlesque.html">Info</a> about Burlesque. Listen to the&nbsp;
        <a href="/data/upload/files/burlesque-def.mp3">Mp3 Midi Version</a>&nbsp;of Burlesque
      </p>
      <h3><a href="/music/azorean-suite/chamarrita.html">Chamarrita</a></h3>
      <p><a href="/music/azorean-suite/chamarrita.html">Info</a> about Chamarrita. Listen to the&nbsp;
        <a href="/data/upload/files/chamarrita-de-sao-jorge-def.mp3">Mp3 Midi Version</a>&nbsp;of Chamarrita
      </p>
      <h3><a href="/music/azorean-suite/tourada.html">Tourada</a>&nbsp;</h3>
      <p><a href="/music/azorean-suite/tourada.html">Info</a> about Tourada. Listen to the&nbsp;
        <a href="/data/upload/files/tourada-def.mp3">Mp3 Midi Version</a>&nbsp;of Tourada
      </p> -->


    </article>

    <article id="klompen" style="display: none">

      <h3>Blues op Klompen</h3>
      <p><img title="Front Sleeve Blues op Klompen" src="assets/img/blues-op-klompen-sleeve-front.jpg" alt="Front Sleeve Blues op Klompen" width="248" height="250" />&nbsp;&nbsp;&nbsp;
        <img title="Back Sleeve Blues op Klompen" src="assets/img/blues-op-klompen-sleeve-back.jpg" alt="Back Sleeve Blues op Klompen" width="248" height="250" />
      </p>
      <p><iframe src="https://www.youtube.com/embed/r054prh82lQ" frameborder="0" width="250" height="206"></iframe>&nbsp;&nbsp;&nbsp; <iframe src="https://www.youtube.com/embed/Vd-LLGD-0fg" frameborder="0" width="252" height="207"></iframe>
      </p>

    </article>

    <article id="pacer" style="display: none">
      <h1>Songs form the CD Pacer together with Frans Wessels</h1>
      <p><img title="Pacer CD Cover " src="assets/img/pacer-cover.jpg" alt="Pacer CD Cover " width="257" height="250" />&nbsp;&nbsp;&nbsp;
        <img title="Pacer CD Back Cover" src="assets/img/pacer-back.jpg" alt="Pacer CD Back Cover" width="250" height="252" />

      </p>
      <p>
        <iframe src="https://www.youtube.com/embed/DVx00_Zfv1Y" frameborder="0" width="250" height="206">
        </iframe>&nbsp;&nbsp;&nbsp; <iframe src="https://www.youtube.com/embed/6qIoPXxNB1s" frameborder="0" width="250" height="206"></iframe>
      </p>
      <p>
        <a href="media/audios/pacer/silly-song.mp3">Silly song</a><br />
        <a href="media/audios/pacer/sad-johanna.mp3">Sad Johanna</a><br />
        <a href="media/audios/pacer/land-of-milk-and-honey.mp3">Land of Milk and Honey</a><br />
        <a href="media/audios/pacer/lady-of-the-moring.mp3">Lady of the Morning</a><br />
        <a href="media/audios/pacer/soldier-of-the-next-milennium.mp3">Soldier of the next Millennium</a><br />
        <a href="media/audios/pacer/le-ephebe.mp3">L'ephebe</a><br /> <a href="/pacer/pacer.mp3">Pacer</a><br />
        <a href="media/audios/pacer/dockyards-of-amsterdam-port.mp3">Dockyards of Amsterdam port</a><br />
        <a href="media/audios/pacer/im-your-programmer.mp3">I'm your programmer</a><br />
        <a href="media/audios/pacer/wandering-man.mp3">Wandering man</a><br />
        <a href="media/audios/pacer/friday-night.mp3">Friday night</a><br />
        <a href="media/audios/pacer/lets-dress-up-as-business-men.mp3">Let's dress up as business men</a>

      </p>


    </article>

    <article id="rare" style="display: none">

      <h1>Rare songs</h1>
      <p>Some songs that were never properly released and some rare recordings
      </p>
      <p><a href="media/audios/rare-songs/life-is-a-bitch.mp3">Life is bitch</a>
      </p>
      <p>For a long time a had a blues band with my brother Ewout. It was called Bottle Steel $ Company.
        I was Bottle, he was Steel, or the other way around. I don't remember. Anyway, this is one of the few surviving recordings and it makes clear why it was such a great pleasure to play in this band.&nbsp;
      </p>
      <p>A live show somewhere in 1995
      </p>
      <p>
        <iframe src="https://www.youtube.com/embed/fBcTDFXj3jo" frameborder="0" width="250" height="206"></iframe>
      </p>
      <p>
        <a href="media/audios/rare-songs/nothing-to-loose.mp3">Nothing to loose</a>
      </p>
      <p>
        This songs deals with my fascination for the sea. It was part of a larger project called Power Rag. Frans and I wanted to revive an electric version of the old ragtime guitar style of Reverend Gary Davis and his peers. We recorded master tapes, but then the studio went bankrupt and the tapes were lost. Some songs we still have in a premix. (The song has been re-recorded for our new&nbsp; BasAlt project: Songs From An Island).
      </p>
      <p>
        <a href="media/audios/rare-songs/rhythm-is-poison.mp3">Rhythm is a poison</a>
        <br />This is one of the most curious songs I ever recorded. I had written a song that was basically only a two-chord riff. I did not know what to do with it. We recorded a base track, but we thought we would never use it. Then we had some extra studio time and my brother Ewout proposed to make the song in to story of two sailors that visit a night club. Listen to our unbridled imagiation, complete with Wendy Double D, a drum solo that goes nowhere, ditto guitar solo and a grande finale.

      </p>

    </article>


    <article id="computer" style="display: none;">

      <h1>Computer music</h1>
      <p>7 Algorithmic improvisations for guitar and/or computer
      </p>
      <p><a href="media/audios/computer-music/ict-improvisation1.mp3">ICT Improvisation 1 (6 min. 51. Sec.)</a>
        <br /> The computer starts with an empty memory. As the guitar plays several successive rule bases of the computer are filled with rules derived from the guitar improvisation. In the first part of the piece a flute, an Aah-voice and a xylophone melody develop randomly. In the second half the xylophone has to follow the guitar. Later the restrictions for the xylophone are relieved and the piece develops in to a rich sound-world of random interactions between the instruments.
      </p>
      <p>
        <iframe src="https://www.youtube.com/embed/5oFpxZIpgig" frameborder="0" width="250" height="206">
        </iframe>

      </p>
      <p>
        <a href="media/audios/computer-music/ict-improvisation2.mp3">ICT Improvisation 2 (2 min. 22 sec.)</a>
        <br /> The piece starts with a background of melodic material learned from improvisations on the theme of a sailor's dance. Against this background the guitar programs a rule base for a slow synthesizer melody.

      </p>
      <p>
        <a href="media/audios/computer-music/ict-improvisation3.mp3">ICT Improvisation 3 (3 min . 12 sec.)</a>
        <br /> The working title of piece could be 'Teasing the violin'. A violin is allowed to improvise freely on a Bach grammar, but only as long as the guitar does not play. As soon as the guitar sounds the violin has to conform to the melodic material of the guitar. The result is quite funny. This is an interesting form of algorithmic anti-improvisation made possible by grammar induction techniques.
      </p>
      <p>
        <a href="media/audios/computer-music/ict-improvisation4.mp3">ICT Improvisation 4 (2 min. 34 sec.)</a>
        <br /> Rule bases for several percussive instruments are filled with material from very fast dance melodies. These instruments are forced to follow the melodic material of the guitar. The setting of the ICT is such that the original melodies are not reproduced but instead a fast random repetition of notes is generated. In this way the guitar is transformed into a percussive instrument. Under each note a random rhythmic pattern is hidden. I imagine that Conlon Nancarrow (1912-1997) would have loved these new possibilities for improvisation.
      </p>
      <p>
        <a href="media/audios/computer-music/ict-composition1.mp3">ICT Composition 1 (1 min. 20 sec.)</a>
        <br /> An example of pure algorithmic composition constructed with ICT. The instrument grammars are derived from the first movement of the second Brandenburg Concerto by Bach (1685-1750). The chorus structure of the piece is based on a composition by Orlando di Lasso (1532-1594): Mon coeur se recommande &agrave; vous. The combination of the two leads to a completely new piece.
      </p>
      <p>
        <a href="media/audios/computer-music/delay-improvition1.mp3">Delay Improvisation 1 (5 min. 43 sec.)</a>
        <br /> Delay is one of the simplest algorithms that can be applied to the sound of an instrument. Yet the possibilities are almost limitless. In this piece the rithmic possibilities of delay are explored. The guitar is a 1975 Howard Robberts Ibanez stereo guitar with a piezo element and a humbucker. The signal is first transformed by a delay box and then fed in to a male-female voice distortion program.
      </p>
      <p>
        <a href="media/audios/computer-music/sequencer-improvisation.mp3">Sequencer Improvisation 1 (3 min. 39 sec.)</a>
        <br /> I always like to improvise on a simple drum pattern. A sequencer program generates the drums. The improvisation is done on a Godin Multiac midi guitar fed in to a Roland GR-09 synthesizer with a mix of base guitar sound and the normal acoustic sound of the guitar itself. The mixed signals give a rich percussive sound that blends well with the drum patterns.
      </p>

    </article>


    <article id="viola" style="display: none;">

      <h2>Viola da Terra&nbsp;</h2>
      <p>
        <img title="Viola da Terra" src="assets/img/screen-shot-2014-09-11-at-082132.png" alt="Viola da Terra" style="width: 100%; height: auto; object-fit: contain;">
        It is believed that the Viola da Terra (or Guitar of the Land) emerged in the Azores in the second half of the fifteenth century, brought by the first settlers in their luggage.<span>&nbsp;At that time the Viola would have been different from the current model, which was honed by the imagination of generations of builders.</span>
      </p>
      <p>
        The Wire Viola, or Viola of Two Hearts, names by which it is also known, was the natural companion of all festive songs, folk dances, love songs and other emotional, lyrical and amorous reveries. It was also used in the so-called &ldquo;derri&ccedil;os&rdquo; (&ldquo;desgarradas&rdquo;, &ldquo;desafios&rdquo; ,&ldquo;despiques&rdquo;) typical Azorean singing battles (not unlike modern rap battles) in which two singers fight for the favor of the audience with lyrics that improvised on the spot.&nbsp;
      </p>
      <p>
        The Viola da Terra was part of the dowry of the groom. During the day it was always placed on top of a checkered quilt as adornment of the bedroom. This union, in which the Viola was placed with the strings facing down, in contact with the fabric of the quilt, prevented, according to old stories, the Viola da Terra to suffer from the humidity, since this instrument is extremely sensitive to climatic variations.
      </p>
      <h3>Revival&nbsp;</h3>

      <p>
        In he past years there has been a revival of the Viola da Terra. Young players like
        <a href="http://www.freewebs.com/violadaterra/apps/blog/">Rafael Carvalho</a> research the old traditional music and create new repertoire. Builders like
        <a href="http://acoresapenoveilhas.blogspot.com/2012/08/na-oficina-de-raimundo-leonardes-no-topo.html">Raimundo Leonardes </a>and&nbsp;
        <a href="http://acoresapenoveilhas.blogspot.com/2013/02/em-casa-de-jose-serpa.html">Jos&eacute;&nbsp;Serpa</a>
        <span>&nbsp;creae a new generation of instruments of high quality often with unusual designs. Here is a fragment of Renato Bettencourt playing solo on modern composition
          <em>"<a href="http://youtu.be/HjiyOzXKhXw">Samba do Porto".</a></em>
        </span>
      </p>

      <p>
        <img title="Nossa Senhora do Mar" src="assets/img/nossa-senhora-do-mar.jpg" alt="Nossa Senhora do Mar" width="300" height="455" />
      </p>

    </article>


  </section>
</main>

<!-- <script src="showandhide.js" defer></script> -->

<script>
  let articles = ["musichome", "basalt", "suite", "klompen", "pacer", "rare", "computer", "viola", ];
  let visibleId = null;

  function show(id) {
    if (visibleId !== id) {
      visibleId = id;

    }
    hide();
  }

  function hide() {
    let div, i, id;
    for (i = 0; i < articles.length; i++) {
      id = articles[i];
      div = document.getElementById(id);
      if (visibleId === id) {
        div.style.display = "block";
      } else {
        div.style.display = "none";
      }
    }
  }


  let articleSuite = ["suiteMain", "madrugada", "tempestade", "burlesque", "chamarrita", "tourada", "moreinfo"];
  let visibleId2 = null;

  function suiteShow(id) {
    if (visibleId2 !== id) {
      visibleId2 = id;

    }
    suiteHide();
  }

  function suiteHide() {
    let div, i, id;
    for (i = 0; i < articleSuite.length; i++) {
      id = articleSuite[i];
      div = document.getElementById(id);
      if (visibleId2 === id) {
        div.style.display = "block";
      } else {
        div.style.display = "none";
      }
    }
  }
</script>


<?= template_footer() ?>