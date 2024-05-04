<?php
include 'functions.php';
?>
<?= template_header('Science') ?>
<?= template_header_other() ?>
<?= template_nav('science') ?>

<main class="rj-black-bg-main">
  <header>
    <h1>Science</h1>
    <img src="assets/img/pieter-adriaans.jpg" alt="Pieter" class="pieter-profile">
    <!-- <div class="rj-science-img">
      </div> -->
  </header>
  <section class="rj-science-section">

    <aside class="rj-main-menu">
      <h3>Menu</h3>
      <ul>
        <li><a href="#main" title="Current research" onclick="show('main')">Current research</a></li>
        <li><a href="#biblio" title="Bibliography" onclick="show('biblio')">Bibliography</a></li>
        <li><a href="#biblioInfo" title="Biographical information" onclick="show('biblioInfo')">Biographical information</a></li>
        <li><a href="#talksEtc" title="Talks, Keynotes, Workshops, Lectures " onclick="show('talksEtc')">Talks, Keynotes, Workshops, Lectures </a></li>
        <li><a href="#projects" title="Projects" onclick="show('projects')">Projects</a></li>
        <li><a href="#patents" title="Patents" onclick="show('patents')">Patents</a></li>
        <li><a href="#fundamentals" title="Fundamental problems in the study of Information and Computation" onclick="show('fundamentals')">Fundamental problems in the study of Information and Computation</a></li>
      </ul>
    </aside>


    <article class="science-article" id="main">
      <h2>Current research interests:</h2>
      <ul>
        <li>
          Philosophy of information
        </li>
        <li>
          Information, Cognition and Art
        </li>
      </ul>

      <h3>Some Publications</h3>

      <div class="rj-flex thumbnails">

        <img title="Cover Handbook of Philosophy of Information" src="assets/img/hpi-cover-big.jpg" alt="Cover Handbook of Philosophy of Information">
        <img title="Cover Data Mining " src="assets/img/data-mining.jpg" alt="Cover Data Mining ">
        <img title="Managing Client/Server" src="assets/img/client-server-management.jpg" alt="Managing Client/Server">
        <img title="Cover Robot op Zee" src="assets/img/robot-op-zee.jpg" alt="Cover Robot op Zee">
        <img title="Cover Painting for the Brain" src="assets\img\Painting-for-the-Brain-cover.jpg" alt="Cover Painting for the Brain">
      </div>

    </article>

    <article id="biblio" class="science-article">

      <h2>Bibliography</h2>

      <h3 style="margin-top: 1rem">20's</h3>

      <ul>

        <li>
          <strong>Daan&nbsp;van &nbsp;den &nbsp;Berg, Pieter&nbsp;Adriaans</strong>, Subset Sum and the Distribution of Information,

          <em><a href="https://bit.ly/3wm34v2">&nbsp;&lsquo;https://bit.ly/3wm34v2.&rsquo;&nbsp;</a></em>


          <em> 13th International Conference on Evolutionary Computation Theory and Applications, </em> 2021.
        </li>
        <li> <strong>P.W. Adriaans</strong>,&nbsp; Differential information theory, </em>
          <em><a href="https://arxiv.org/abs/2111.04335">&nbsp;&lsquo;https://arxiv.org/abs/2111.04335.&rsquo;&nbsp;</a></em>
          <em> ArXiv, 2021. &nbsp;</em>
        </li>

        <li> <strong>P.W. Adriaans </strong>,&nbsp; A computational theory of meaning</em>
          <em>, in</em><em>&nbsp;&lsquo;Advances in Info-Metrics: Information and Information Processing across Disciplines.&rsquo;&nbsp;</em>
          <em style="font-size: 0.9rem;">Editors: Min Chen, Michael Dunn, Amos Golan and Aman Ullah, OUP, </em>
          2020. &nbsp;
        </li>
      </ul>

      <h3 style="margin-top: 1rem">10's</h3>

      <ul>
        <li> <strong>P.W. Adriaans</strong>,
          "<a title="SEP Information " href="https://plato.stanford.edu/archives/spr2019/entries/information/">Information</a>",
          &nbsp;The Stanford Encyclopedia of Philosophy&nbsp;(Spring 2019 Edition), Edward N. Zalta&nbsp;(ed.)</em>
        </li>
        <li><strong>P. W. Adriaans</strong><span style="font-size: 0.9rem;">, </span>
          <a style="font-size: 0.9rem;" title="Fueter-Polya" href="https://arxiv.org/abs/1809.09871">A simple information theoretical proof of the Fueter-Polya conjecture</a><span style="font-size: 0.9rem;">, 2018.</span>
        </li>
        <li>
          <strong>P.W. Adriaans, </strong><span style="font-size: 0.9rem;">A General Theory of Information and Computation, eprint </span><a style="font-size: 0.9rem;" title="A General Theory of Information and Computation" href="https://arxiv.org/abs/1611.07829">arXiv:1611.07829</a><span style="font-size: 0.9rem;">, November, 2016.&nbsp;</span>
        </li>
        <li>
          <strong>Peter&nbsp;Bloem, Steven&nbsp;de &nbsp;Rooij, Pieter&nbsp;Adriaans</strong>, Two Problems for Sophistication, <a href="http://link.springer.com/book/10.1007/978-3-319-24486-0">Algorithmic Learning Theory</a>, Volume 9355 of the series <a href="http://link.springer.com/bookseries/558">Lecture Notes in Computer Science</a> pp 379-394, 2015. (Find related material <a href="http://peterbloem.nl/publications/two-problems">here</a>).
        </li>
        <li>
          <strong>Peter&nbsp;Bloem, Francisco&nbsp;Mota, Steven&nbsp;de&nbsp;Rooij, Lu&iacute;s&nbsp;Antunes, Pieter&nbsp;Adriaans,&nbsp;</strong><a href="http://link.springer.com/book/10.1007/978-3-319-11662-4">Algorithmic Learning Theory</a><span>,&nbsp;A Safe Approximation for Kolmogorov Complexity,&nbsp;Volume 8776 of the series&nbsp;</span><a href="http://link.springer.com/bookseries/558">Lecture Notes in Computer Science</a><span>&nbsp;pp 336-350, 2014.&nbsp;</span>
        </li>
        <li>
          <strong>P.W. Adriaans, </strong><a title="www.schilderenvoorhetbrein.nl/" href="http://www.schilderenvoorhetbrein.nl/">Schilderen voor het Brein</a><span>, Uitgeverij Mooi Media, 2013.&nbsp;</span>
        </li>
        <li>
          <strong>P.W.Adriaans</strong><span>, </span><a title="Pleidooi voor het trage kijken" href="assets/pdfdoc/documents/fd20131123.pdf">Pleidooi voor het trage kijken</a><span>, Friesch Dagblad, 23 november, 2013.&nbsp;</span>
        </li>
        <li>
          <strong>P.W. Adriaans,</strong><span>&nbsp;"</span><a href="https://plato.stanford.edu/entries/information/">Information</a><span>",&nbsp;</span><em>The Stanford Encyclopedia of Philosophy</em><span>&nbsp;(Winter 2012 Edition), Edward N. Zalta&nbsp;(ed.),</span><span>&nbsp;2016.&nbsp;</span>
        </li>
        <li>
          <strong>P.W. Adriaans,&nbsp;</strong><span>Facticity as the amount of self-descriptive information in a data set</span><strong>,&nbsp;<a href="http://arxiv.org/abs/1203.2245">arXiv:1203.2245</a>&nbsp;[cs.IT],</strong><span>&nbsp;2012.</span>
        </li>
        <li>
          <strong>P.W. Adriaans, P.E. Van Emde Boas</strong><span>, Information, <a href="assets/pdfdoc/metacompuatation.pdf">Computation and the Arrow of time</a>, in COMPUTABILITY IN CONTEXT, Computation and Logic in the Real World, edited by S. Barry Cooper (University of Leeds, UK) &amp; Andrea Sorbi (Universita degli Studi di Siena, Italy), 2011.</span>
        </li>
        <li>
          <strong>P.W. Adriaans, Wico Mulder</strong><span>, MDL in the limit, in ICGI 2010,&nbsp;</span><a href="http://link.springer.com/book/10.1007/978-3-642-15488-1">Grammatical Inference: Theoretical Results and Applications</a><span>,&nbsp;</span><span>Volume 6339 of the series&nbsp;</span><a href="http://link.springer.com/bookseries/558">Lecture Notes in Computer Science</a><span>&nbsp;pp 258-261, 2010.</span>
        </li>
        <li>
          <strong>W. Mulder, P.W. Adriaans&nbsp;</strong><span>, Using grammar induction to model adaptive behaviour of networks of collaborative agents, ICGI 2010,&nbsp;<a href="http://link.springer.com/book/10.1007/978-3-642-15488-1">Grammatical Inference: Theoretical Results and Applications</a>,&nbsp;Volume 6339 of the series&nbsp;<a href="http://link.springer.com/bookseries/558">Lecture Notes in Computer Science</a>&nbsp;pp 163-177, 2010.</span>
        </li>
        <li>
          <strong>P.W. Adriaans&nbsp;</strong><span>, <a title="A critical analysis of Floridi" href="https://link.springer.com/content/pdf/10.1007%2Fs12130-010-9097-5.pdf">A critical analysis of Floridi's theory of semantic information, In Knowlegde, Technology and Policy</a>, Hilmi Demir ed. : Luciano Floridi's Philosophy of Technology: Critical Reflections, 2010, Volume 23, Issue 1-2, pp. 41-56..</span>
        </li>
      </ul>
      <ul>
        <li><strong><strong></strong></strong><strong></strong></li>
      </ul>



      <h3>00's</h3>
      <ul>
        <li>
          <strong>P.W. Adriaans </strong>, <a title="Between order and Chaos" href="assets/pdfdoc/documents/special-issuecie2007-20.pdf">Between Order and Chaos: The Quest for Meaningful Information, Theory of Computing Systems,</a> Volume 45 , Issue 4 (July 2009), Special Issue: Computation and Logic in the Real World; Guest Editors: S. Barry Cooper, Elvira Mayordomo and Andrea Sorbi Pages 650-674, 2009
        </li>
        <li>
          <strong>Adriaans, P. Vitanyi, P.</strong>, Approximation of the Two-Part MDL Code, Comput. Sci. Dept., Univ. of Amsterdam, Amsterdam; Information Theory, IEEE Transactions on, Volume: 55, Issue: 1, On page(s): 444-457. 2009
        </li>
        <li>
          <strong>P.W. Adriaans, J.F.A.K. van Benthem (eds.), </strong> <a href="http://www.illc.uva.nl/HPI/">Handbook of Philosophy of Information</a>, Elseviers Science Publishers, 2008
        </li>
        <li>
          <strong>P.W. Adriaans,</strong> The philosophy of learning, the cooperative computational universe. <a href="http://www.illc.uva.nl/HPI/Draft_philosophy_of_learning.pdf">Pdf</a>, in Handbook of Philisophy of Information, P.W.Adriaans, J.F.A.K. van Benthem (eds.), Elseviers Science Publishers, 2008
        </li>
        <li>
          <strong>S. Katrenko and P. Adriaans.</strong> A Local Alignment Kernel in the Context of NLP. In Proceedings of the 22nd International Conference on Computational Linguistics (Coling), 2008.
        </li>
        <li>
          <strong>S. Katrenko and P. Adriaans.</strong> Semantic Types of Some Generic Relation Arguments: Detection and Evaluation. In Proceedings of the 46th Annual Meeting of the Association for Computational Linguistics: Human Language Technologies (ACL/HLT), 2008.
        </li>
        <li>
          <strong>S. Katrenko and P. Adriaans.</strong> Qualia Structures and their Impact on the Concrete Noun Categorization Task. In Proceedings of the "Bridging the gap between semantic theory and computational simulations" workshop at ESSLLI 2008.
        </li>
        <li>
          <strong>S. Katrenko and P. Adriaans.</strong> Named Entity Recognition for Ukrainian: A Resource-Light Approach. In Proceedings of the BSNLP workshop, ACL 2007.
        </li>
        <li>
          <strong> S. Katrenko and P. Adriaans.</strong> Using Semi-Supervised Techniques to Detect Gene Mentions. In Proceedings of the Second BioCreative Challenge Workshop, April 2007.
        </li>
        <li>
          <strong>P.W. Adriaans, </strong>Learning deterministic DEC grammars is learning rational numbers, In Proceedings of the 8th International Colloquium on Grammatical Inference (ICGI). Lecture Notes in Artificial Intelligence, Sakaibara, Y.; Kobayashi, S.; Sato, K.; Nishino, T.; Tomita, E. (Eds.). Tokyo, Japan, September 21, 2006. LNAI, vol. 4201
        </li>
        <li>
          <strong>P.W. Adriaans, C. Jacobs, </strong> Using MDL for grammar induction, , In Proceedings of the 8th International Colloquium on Grammatical Inference (ICGI). Lecture Notes in Artificial Intelligence, Sakaibara, Y.; Kobayashi, S.; Sato, K.; Nishino, T.; Tomita, E. (Eds.). Tokyo, Japan, September 21, 2006. LNAI, vol. 4201
        </li>
        <li>
          <strong>P.W. Adriaans, </strong> SSTT, Speed Search in Truth Tables, a complete inductive approach to SAT, in Proceedings of the Fifteenth Dutch-Belgian Conference on Machine Learning (Benelearn), 2006.
        </li>
        <li>
          <strong>S. Katrenko, and P. Adriaans.</strong> Grammatical Inference in Practice: A Case Study in the Biomedical Domain. In Proceedings of the 8th International Colloquium on Grammatical Inference (ICGI). Lecture Notes in Artificial Intelligence, Sakaibara, Y.; Kobayashi, S.; Sato, K.; Nishino, T.; Tomita, E. (Eds.). Tokyo, Japan, September 21, 2006. LNAI, vol. 4201
        </li>
        <li>
          <strong>S. Katrenko, and P. Adriaans.</strong> Using Maximal Embedded Syntactic Subtrees for Textual Entailment Recognition. Proceedings of RTE-2 workshop, 2006.
        </li>
        <li>
          <strong>Frank Terpstra, Pieter Adriaans, </strong> "Designing Workflow Components for e-Science," /e-science/, p. 10, Second IEEE International Conference on e-Science and Grid Computing (e-Science'06), 2006.
        </li>
        <li>
          <strong>S. Katrenko and P. Adriaans.</strong> Pattern Acquisition from the Dependency Paths (Extended Abstract). Presented at the workshop "Ontologies in Text Technology", Osnabr&uuml;ck, Germany, September 28, 2006.
        </li>
        <li>
          <strong>S. Katrenko, M. Scott Marshall, Marco Roos and Pieter Adriaans.</strong> Learning Biological Interactions from Medline Abstracts. (Extended Abstract) In Proceedings of the 18th Belgian-Dutch Conference on Artificial Intelligence (BNAIC-2006), Namur, Belgium, October 2006.
        </li>
        <li>
          <strong>S. Katrenko and P. Adriaans.</strong> Learning Relations from Biomedical Corpora Using Dependency Tree Levels. In Proceedings of the Fifteenth Dutch-Belgian Conference on Machine Learning (Benelearn), Ghent, Belgium, May 12, 2006.
        </li>
        <li>
          <strong>S. Katrenko and P. Adriaans.</strong> Using Maximal Embedded Syntactic Subtrees for Textual Entailment Recognition. In Proceedings of RTE-2 workshop, Venice, Italy, April 10, 2006.
        </li>
        <li>
          <strong>Sophia Katrenko, M. Scott Marshall, Marco Roos and Pieter Adriaans.</strong> Learning Biological Interactions from Medline Abstracts. In Proceedings of the ICML'05 workshop "Learning Language in Logic", Bonn, Germany, August 7, 2005.
        </li>
        <li>
          <strong>P. Adriaans </strong>&lsquo;<a href="http://www.ziedaar.nl/index.php?theme=7&amp;id=230" target="_BLANK">De filosofie van het leren</a>&rsquo; in Blind Interdiciplinair tijdschrift. Januari 2006.
        </li>
        <li>
          <strong>P.Adriaans, </strong>Onderwijs wordt met kruideniers mentaliteit gewogen, Automatisering Gids, 8 april 2005.
        </li>
        <li>
          <strong>P.Adriaans, </strong>Informatica als de wetenschap van alles, Automatisering Gids, 25 februari 2005.
        </li>
        <li>
          <strong>Lavrac, N., Motoda, H., Fawcett, T., Holte, R., Langley, P., &amp; Adriaans, P. </strong> Lessons learned from data mining applications and collaborative problem solving. Machine Learning, 57, 13-34, 2004.
        </li>
        <li>
          <strong>Adriaans, P. &amp; Zaanen, M. van </strong> Computational Grammar Induction for Linguists. Grammars; special issue with the theme "Grammar Induction", 7, 57-68, 2004.
        </li>
        <li>
          <strong>Adriaans, P., Fernau, H., Higuera, C. de la &amp; Zaanen, M. van </strong> Introduction to the Special Issue on Grammar Induction. Grammars; special issue with the theme "Grammar Induction", 7, 41-43, 2004
        </li>
        <li>
          <strong>Pieter Adriaans,</strong> Robot op zee, Amsterdam, Boom, 2003.
        </li>
        <li>
          <strong>Pieter Adriaans,</strong> Backgrounds and General Trends. In Dealing with the Data Flood (pp. 16-25). The Hague: STT Beweton, 2002.
        </li>
        <li>
          <strong>Pieter Adriaans,</strong> Data mining in industry, lessons learned, in Machine Learning Journal, to appear.
        </li>
        <li>
          <strong>Pieter Adriaans,</strong> Grammar Induction for Linguists, in Grammars, to appear.
        </li>
        <li>
          <strong>Adriaans, Pieter W.,</strong> From Knowledge-based to Skill-based Systems, Sailing as a machine learning challenge, in Proceedings of the ECML/PKDD 2003, Dubrovnik 2003.
        </li>
        <li>
          <strong>Adriaans, Pieter W.,</strong> Production Control, in Handbook of Data Mining and Knowledge Discovery, Willi Kl&ouml;sgen and Jan M. Zytkow ed., Oxford University Press 2002.
        </li>
        <li>
          <strong>Van Aartrijk, Martijn, L., Claudio P. Tagliola and Pieter W. Adriaans,</strong> AI on the Ocean: the Robosail project, in ECAI 2002, Frank van Harmelen ed., pg. 653-657, IOS Press 2002.
        </li>
        <li>
          <strong>Adriaans, P.W.,</strong> Backgrounds and General Trends, in Dealing with the Dataflood, Mining Data, Text and Multimedia, Jeroen Meij ed. pg. 16-25, STT Beweton, The Hague, Netherlands, 2002.
        </li>
        <li>
          <strong>Adriaans, P.W., Henning Fernau, Menno van Zaanen</strong> Grammatical Inference: Algorithms and applications, 6th Proceedings of the International Colloqium, ICGI 2002. Amsterdam, September, Berlin/Heidelberg: Springer Verlag, 2002.
        </li>
        <li>
          <strong>P.W. Adriaans</strong> Multimedia: het gezicht van de toekomst, op weg naar een perfecte synergie tussen mens en computer, Next Generation Scenario, Ministerie van Economische zaken, 2002 (www.cic-online.nl/files/NGSmultimedia.pdf)
        </li>
        <li>
          <strong>Adriaans, Pieter,</strong> Semantic Induction with EMILE, Opportunities in Bioinformatics, TWLT19, Information Extraction in Molecular Biology, Proceedings Twente Workshop on Language Technology 19, ESF Scientific Programme on Integrated Approaches for Functional Genomics, ed. Paul van der Vet et al, pg. 1-6, Enschede, 2001",
        </li>
        <li>
          <strong>Zaanen, Menno van and Adriaans, Pieter,</strong> Alignment-Based Learning versus EMILE: A Comparison, in Proceedings of the 13th Dutch-Belgian Artificial Intelligence Conference, Ben Kr&ouml;se et al. De Rode Hoed, Amsterdam, pg. 315-322, 25-26 October 2001.
        </li>
        <li>
          <strong>Adriaans, Pieter,</strong> Learning Shallow Context-free Languages under Simple Distributions, Algebras, Diagrams and Decisions in Language, Logic and Computation, CSLI/CUP", Ann Copestake and Kees Vermeulen (eds.)", 2001.
        </li>
        <li>
          <strong>Adriaans, Pieter and Haas, Erik de,</strong> Learning from a Substructural Perspective", Proceedings of the Fourth Conference on Computational Natural Language Learning and of the Second Learning Language in Logic Workshop, Lisbon, Portugal, 2000, Cardie, Claire et al ed., pg. 176--183, September 2000.
        </li>
        <li>
          <strong>Adriaans, Pieter and Erik de Haas,</strong> Grammar Induction as Substructural Inductive Logic Programming, In Learning Language in Logic (by Cussens, James et al), Lecture Notes in Artificial Intelligence, Vol. 1925, pg. 127-142, Berlin: Springer Verlag, 2000.
        </li>
        <li>
          <strong>Trautwein, M.H., Adriaans, P.W., &amp; Vervoort M.R.</strong> Towards high speed grammar induction on large text corpora. In G.K. Hlavac. V. Feffrey, &amp; J. Wiederman (Eds.), Lecture Notes SOFSEM 2000: Theory and practice of Informatics, Vol. 1963, Lecture Notes in Computer Science, (pp. 173-186), Berlin: Springer Verlag, 2000.
        </li>
      </ul>



      <h3>90's</h3>

      <ul>
        <li>
          <strong>Haas, Erik de and Adriaans, Pieter,</strong> Substructural Logic: a unifying framework for second generation datamining algorithms, Proceedings of the Twelfth Amsterdam Colloquium,1999,Dekker, Paul ed. pg. 121-126, University of Amsterdam, ILLC, Department of Philosophy", 1999.
        </li>
        <li>
          <strong>Adriaans, Pieter,</strong> De mens en moderne communicatiemedia, De kabel: Kafka in de polder by Drake, Anthonie et al., pg. 163-172, 1999.
        </li>
        <li>
          <strong>Adriaans, Pieter,</strong> Industrial requirements for ML application technology. Proceedings of the Workshop on Machine Learning Application in the real world; Methodogical Aspects and Implications, Engels, Robert et al. ed., Nashville, TN, 1997", International Conference on Machine Learning, ICML97", 1997.
        </li>
        <li>
          <strong>Adriaans, Pieter,</strong> Filosofische aspecten van het ontwerpen van informatie systemen, Filosofie, tweemaandelijks tijdschrift van de stichting informatie filosofie, vol. 7, pg. 35--38,1997.
        </li>
        <li>
          <strong>Adriaans, Pieter,</strong> De computer als model en als hulpmiddel, in Techniek en informatisering, het denken van Heidegger, Th.C.W. Oudemans ed., pg. 12-24, 1997".
        </li>
        <li>
          <strong>Adriaans, Pieter,</strong> Bewustzijn en cognitieve kunstmatige intelligentie, Het Bewustzijn, Bureau Studium Generale of the University Utrecht, 1997, pg. 67-87.
        </li>
        <li>
          <strong>Adriaans, Pieter and Knobbe, Arno J.,</strong> EMILE: Learning Context-free Grammars from Examples, Proceedings of BENELEARN-96, Maastricht",1996", Herik, H.Jaap van den et al ed., pg. 105--115, University Maastricht, The Netherlands, 1996.
        </li>
        <li>
          <strong>Adriaans, Pieter,</strong> Adaptive System Management, Advances in Applied Ergonomics, Proceedings of the 1st International Conference on Applied Ergonomics, Istanbul Turkey, pg. 485--490, 1996.
        </li>
        <li>
          <strong>Senf, Wouter and Adriaans, Pieter,</strong> Support for data mining algorithms in a relational environment, Data Warehouse Report,1996,vol. 6, pg. 6-8.
        </li>
        <li>
          <strong>Heijer, Eelco den and Adriaans, Pieter.</strong> The Application of Genetic Algorithms in a Carreer Planning Environment: CAPTAINS, International Journal of Human-Computer Interaction,1996, volume 8, pg. 343--360, July-August", 1996.
        </li>
        <li>
          <strong>Adriaans, Pieter,</strong> Predicting pilot bid behavior with genetic algorithms (Abstract), Symbiosis of Human and Artifact, Proceedings of the Sixth International Conference on Human-Computer Interaction, Anzai, Yuichiro et al, (HCI International '95), pg. 1109-1113, Tokyo, Japan, 1995,
        </li>
        <li>
          <strong>Pieter Adriaans and Sylvia Janssen and Erik Nomden,</strong> Effective identification of semantic categories in curriculum texts by means of cluster analysis, ECML-93, European Conference on Machine Learning, Workshop notes Machine Learning Techniques and Text Analysis, ="Department of Medical Cybernetics and Artificial Intelligence, University of Vienna in cooperation with the Austrian Research Institute for Artificial Intelligence,Vienna, Austria. pg. 37-44,1993".
        </li>
        <li>
          <strong>Adriaans, Willem, Pieter,</strong> Language Learning from a Categorial Perspective, Phd Thesis, Universiteit van Amsterdam,1992.
        </li>
        <li>
          <strong>Adriaans, P.W.,</strong> A Domain Theory for Categorial Language Learning Algorithms, in Proceedings of the Eighth Amsterdam Colloquium, P. Dekker and M. Stokhof (eds.), University of Amsterdam, 1992. <a href="http://staff.science.uva.nl/%7Epietera/publications/coll.ps">Postscript</a>
        </li>
        <li>
          <strong>Adriaans, P.W.,</strong> Bias in Inductive Language Learning, Proceedings of the ML92 Workshop on Biases in Inductive Language Learning, Diana F. Gordon ed., Aberdeen 1992.
        </li>
        <li>
          <strong>Adriaans, Pieter,</strong> Visual Programming and Knowledge Manipulation, Proceedings Share Europe Anniversary meeting Amsterdam, Managing your DP Assets", 1991, Share Europe (Seas)", vol. 2", pg. 525-551, 1991.
        </li>
        <li>
          <strong>Adriaans, P.W.,</strong> Categoriale modellen voor kennissystemen, Informatie, maandblad voor gegevens verwerking, pg. 118-126, 1990.
        </li>
      </ul>

      <h3>80's</h3>
      <ul>
        <li>
          <strong>Adriaans, Pieter",</strong> EMILE (Entity Modelling Intelligent Learning Engine), An experimental interpreter for categorical languages based on generalized semantical resolution and unification, Benelog, Research on and applications of Logic Programming in Belgium and the Netherlands, The contact group Artificial Intelligence of the Belgian National Fund for Scientific Research, Catholic University of Leuven, K.U. Leuven, Department of Computer Science Heverlee, Belgium,1989.
        </li>
        <li>
          <strong>Adriaans, P.W.,</strong> Kategoriale Kennissystemen, in Proceedings AI toepassingen '88, eerste nederlandse conferentie.
        </li>
        <li>
          <strong>Adriaans, P.W.,</strong> Waarheid en Dialoog, Thauma, no. 12, pg. 104-120, 1987.
        </li>
        <li>
          <strong>Adriaans, P.W.,</strong> The Manipulation of Symbols and the Metaphor of the Thinking Machine, La Linea, Leiden, pg. 7-21, 1986.
        </li>
        <li>
          <strong>Adriaans, P.W.,</strong> Taal en solipsisme in de filosofie van J.A. D&egrave;r Mouw, Algemeen Nederlands Tijdschrift voor Wijsbegeerte, no. 1, pg. 1-20, 1986.
        </li>
        <li>
          <strong>Adriaans, P.W.,</strong> Enige notities bij de filosofie van J.A.D&egrave;r Mouw, in Over J.A. d&egrave;r Mouw, Beschouwingen, BZZT&ocirc;H Literair archief, 's Gravenhage, 1984.
        </li>
      </ul>

    </article>

    <article id="biblioInfo"  class="science-article">

      <h2>Biographical and Background Information</h2>

      <p>

        Pieter Willem Adriaans
        <br />Professor emeritus University of Amsterdam
        <br />
        Cais das Manadas
        <br />
        9800-036 Manadas
        <br />
        S&atilde;o Jorge
        <br />
        Azores, Portugal
        <br />
        www.pieter-adriaans.com
        <br />
        Mobile Phone &nbsp;&nbsp; &nbsp;+31 6 54234459, +352 964 643 610
        <br />
        Email: pieter@pieter-adriaans.com
        <br />
        <br />
        <strong>Education</strong>
        <br />
        Undergraduate Institution(s) Kandidaats Filosofie, University of Leiden, 1979
        <br />
        Graduate Institution(s) Doctoraal Filosofie, University of Leiden, 1983
        <br />
        University of Amsterdam, Computer Science PhD, 1992
        <br />
        <br />
        <strong>Appointments</strong>
        <br />
        1998-2021, Professor, Machine Learning/Artificial Intelligence,
        <br />
        2006-2008 Director IvI, University of Amsterdam
        <br />
        <span>
          2003-2008 Chairman, CWI (National research center for mathematics and computer science), Amsterdam
          <br />
        </span>
        <span>
          1989-2000 Managing Director/Founder Syllogic B.V.&nbsp;
          <br />
        </span>
        <span>
          1985-1989, General Manager of Compu&rsquo;Disc, General Manager at Info&rsquo;Products Informatica Diensten
          <br />
        </span>
        <span>1983-1985, Software Developer, Service Manager at Buro Microsoftware</span>
      </p>
      <p>
        <strong>Honors and Fellowships</strong>
        <br />
        Advisory Board, Info-Metrics Institute, American University, Washington D.C. (2010-)
        <br />
        Member AcTI Netherlands Academy of Technology and Innovation (2004-2012)
        <br />
        Member AWT Advisory Council of Science and Technology Policy (2004-2012)
        <br />
        Project Leader Dutch Prognostics and Health Monitoring Joint Strike Fighter (1997-2001)
        <br />
        <br />
      </p>
      <p>
        <strong>Graduate Advisors:</strong>
        <br />
        Prof. Dr. M.Fresco, emeritus University of Leiden
        <br />
        Prof. Dr. H. Philipse, University of Utrecht
        <br />
        Postdoctoral Sponsors:
        <br />
        Prof. Dr. P.E. Van Emde Boas, emeritus University of Amsterdam
      </p>
      <p>
        <strong>Phd Students</strong>
        <br />
        Past:
        <br />
        S. Katrenko, University of Utrecht.
        <br />
        N. Netten, SPIE Nederland B.V.
        <br />
        G. de Vries, University of Amsterdam
        <br />
        W. Mulder, Logica CMG
        <br />
        F. Terpstra, Cap Gemini
        <br />
        P. Bloem, Vrije Universiteit Amsterdam&nbsp;
      </p>
      <p>
        <strong>&nbsp;Short Scientific Biography</strong>
      </p>
      <p align="JUSTIFY">

        Pieter Adriaans (1955) attended the Johan de Witt Gymnasium in Dordrecht.&nbsp;
        <span>He studied philosophy and mathematics in Leiden, the Netherlands, under Nuchelmans and van Peursen. He was research assistent of Fresco for a while with the study of the philosophical estate of the well known Dutch philosopher and poet Johan Adreas D&eacute;r Mouw 1863-1919 as a special assignment. In 1983 he graduated and started to work as a software developer, and later service manager for Buro Microsoftware. In 1985 he became general manager of Compu'Disc and later general manager of Info'Products Informatica Diensten. He has been active in research in the areas of artificial intelligence and relational database systems since 1984. He and his business partner, Dolf Zantinge, founded Syllogic B.V. in 1989. In 1992 Adriaans received a PhD in computer science at the University of Amsterdam, where he has been professor of machine learning/artificial intelligence since 1998. During his years in Syllogic he coordinated and participated in about 200 projects concerning the industrial applications of AI and machine learning techniques. Dolf and Pieter sold Syllogic to Perot Systems (www.perotsystems.com) in 1997, and stayed on as managing directors - a transaction which officially included time off for Pieter to sail the Singlehanded TransAtlantic Race 2000. For this race he developed the Syllogic Sailing Lab, the most advanced open 40 racing yacht around at that time (</span>
        <a href="http://www.robosail.com/" target="_BLANK">www.robosail.com</a>
        <span>
          ). In this project he combined his skills in machine learning with his love of sailing to create a racing yacht that could learn to optimize its behavior. He was leading the VL-e (Virtual lab for e-science) project at the institute for computer science at the university of Amsterdam and participated in the
          <a title="Commit/Adriaans" href="http://www.commit-nl.nl/people/adriaans">Commit</a>
          project.&nbsp;
        </span>
      </p>
      <p>
        &nbsp;He holds several patents on adaptive systems management and on a method for automatic composition of music using grammar induction techniques. Adriaans acted as project leader for various large international R&amp;D projects: amongst others, the development of distributed database management software in co-operation with IBM and Prognostic and Health management for the Joint Strike Fighter. He wrote numerous articles and a number of books on topics related to both computer science and philosophy, including a book on systems analysis and books on client/server and distributed databases as well as data mining. He is editor of the Handbook of Philosophy of Information, a project of Elseviers Science Publishers and of the lemma on Information in the Stanford Encyclopedia of philosophy.&nbsp;
      </p>
      <p>
        Currently he is primarily interested in Philosophy of Information and learning as data compression using the theory of Kolmogorov complexity as a guiding principle.&nbsp; In 2008 Adriaans decided to step down from almost all of his managerial responsibilities and dedicate his life to the combination of science and art. He currently lives on th Azores where he exploits a cultural centre (Atelier de Kaasfabriek) on the island of S&atilde;o Jorge with his wife Rini.&nbsp;
      </p>
      <p>
        <strong>&nbsp;Some projects</strong>
      </p>
      <p align="JUSTIFY">

        From 1987 till 1992 a team under Adriaans' supervision created OBIS (Opleidingen Beroepen Informatie Systeem) for the SLO (Stichting Leerplan Ontwikkeling) in the Netherlands.&nbsp; This was a database and expert system facilitating the development of vocational training and education profiles.
        <br />
        As a result of his Phd research he developed the EMILE language learning algorithm and embedded it in a toolbox.&nbsp;
        <br />
        2002 - At the university of Amsterdam Adriaans developed the course &lsquo;learning and deciding&rsquo; in which groups of graduate students cooperate with representatives from industry to solve practical problems on real life data sets using data mining techniques.&nbsp;
        <br />
        From 1997 till 2005 he supervised the Robosail (www.robosail.com) project, in which a self learning race yacht was built. The project gathered a lot of publicity was used extensively to train students. &nbsp;
        <br />
        From 2002 till 2007 he was involved as a project leader in the Vl-e project, Virtual lab for e-science. In 2008 he was co-editor of the Handbook of Philosophy of Information (Elseviers Science Publishers). &nbsp;From 2012 till 2017 he worked in the Commit project.&nbsp;
      </p>
      <p>
        <strong>&nbsp;Recent Activities </strong>
      <p>
        <span>Originally the chair &ldquo;Learning and adaptive systems&rdquo; had a strong industrial component, related to commercial machine learning and data mining activities. In the past decennium after the release of the &ldquo;Handbook of Philosophy of Information&rdquo; the focus of attention has shifted to cognition and philosophy of information. In short, his research program is the application of insights of algorithmic complexity theory to the analysis of issues in cognition and philosophy.&nbsp;</span>
      </p>
      <p>

        Pivotal is the lemma on information that Adriaans wrote for the Stanford Encyclopedia of Philosophy<br>
        <a href="http://plato.stanford.edu/entries/information/">(http://plato.stanford.edu/entries/information/)</a>.<br> This article gives, for the first time,
      <ol type="1">
        <li> A comprehensive overview of the development of the notion of information from antiquity to modern times, both from the perspective of history of ideas and the development of the terminology.</li>
        <li> An extensive analysis of the emergence of the formal notion of information in the 20<sup>th</sup> century in the context of algorithmic complexity theory.</li>
      </ol>
      </p>
      <p>

        In the past decade Adriaans has been working on several open problems in philosophy of information, specifically the issue of meaningful information, and the issue of the interaction between information and computation. Some preliminary results have been published
        <a href="#biblio" onclick="show('biblio')">(bibliography)</a> but the bulk of the work is still under construction<a href="#projects">(projects)</a>.
        The research into the definition of meaningful information by means of two-part code optimization<a href="http://arxiv.org/abs/1203.2245">(http://arxiv.org/abs/1203.2245)</a>
        was seriously impaired by the growing concern that an a priori separation of a data set in a structural part and an ad hoc part was not mathematically feasible. In 2017 Adriaans succeeded in proving this conjecture: using generalizations of the Cantor pairing function any data set can be split into any set of parts at constant cost. The results have been published as a book chapter:


      <p>
        <li><strong>P. W. Adriaans</strong>,&nbsp;<em> A computational theory of meaning, Advances in Info-Metrics: Information and Information Processing across Disciplines.&rsquo;&nbsp;,</em> Ullah, OUP, 2020. &nbsp;
        </li>

      <p>
        An extensive paper on the interaction of information and computation is in preparation. A preliminary version can be found at:

      <p>
        <li><strong>P. W. Adriaans</strong>, Differential information theory, <a href="https://arxiv.org/abs/2111.04335">https://arxiv.org/abs/2111.04335</a>.</em>
          ArXiv, 2021.
        </li>


      </p>
      <p>
        Apart from this work Adriaans has been involved in a number of smaller projects. Amongst others:&nbsp;
      </p>

      <ul>
        <li>

          A book &lsquo;Schilderen voor het brein&rsquo; (in Dutch) was published in 2013. An <a href="http://www.paintingforthebrain.com">English translation</a> is in preparation. This book generated some publicity which lead to a number of public lectures, and courses on creativity, e.g.:&nbsp;
          <br />
          Studium Generale Utrecht: <br><a href="https://www.sg.uu.nl/sprekers/pieter-adriaans">https://www.sg.uu.nl/sprekers/pieter-adriaans</a> <br>

          Instituut voor beeldtaal,<br>
          <a href="http://www.instituutvoorbeeldtaal.nl/artikelen/prof-pieter-adriaans-zien-we-beeldig-of-zien-we-talig/">http://www.instituutvoorbeeldtaal.nl/artikelen/prof-pieter-adriaans-zien-we-beeldig-of-zien-we-talig/</a>
        </li>
        <li>

          A philosophical interpretation of the poetry of J.A. D&egrave;r Mouw (
          <a href="assets/pdfdoc/papers-under-construction-/der-mouw-final.pdf">der-mouw-final.pdf</a>
          ) written for the 2014 D&egrave;r Mouw symposium. As an assistant in Leiden Adriaans has made an extensive analysis of the unpublished philosophical manuscripts of D&egrave;r Mouw and at this moment he is one of the few persons alive with some deeper understanding of the philosophical background of this oeuvre. To be published.&nbsp;

        </li>
        <li>

          A course &lsquo;Philosophy of Art&rsquo; that Adriaans teaches at the Foudgumse School (
          <a href="http://defoudgumseschool.nl/website/">http://defoudgumseschool.nl/website/</a>
          ) on a yearly basis.&nbsp;

        </li>
      </ul>
      <p>
        <strong>Background information&nbsp;</strong>
      <p>
        <span>In the coming years Adriaans hopes to continue and combine his work as an artist, philosopher and scientist.&nbsp;&nbsp; His current practice has a strong international orientation and his output consists of scientific papers, books, paintings, exhibitions, and musical performances, courses and lectures. Only a small part of these activities fit in to an academic setting.&nbsp;</span> In 2015 Adriaans and his wife Rini founded a cultural center at the island of S&atilde;o Jorge on the Azores. The ideal is to combine art, science and business in to a sustainable, eco-friendly, artistically rewarding and financially healthy venture.
      </p>

    </article>
    <article id="talksEtc" class="science-article">

      <h2>Talks, Keynotes, Workshops</h2>

      <ul>

        <li>
          2022 January ILLC Course,<a href="https://msclogic.illc.uva.nl/current-students/courses/previous-projects/project/200/2nd-Semester-2021-22-Philosophy-of-information-and-the-P-vs-NP-problem">Philosophy of Information and the P vs NP problem.&nbsp;</a>

        </li>


        <li>
          2019 The<a title="THe business of Nature Forum " href="https://geoversity.org/en/executive-programs/nature-of-business"> Businiess of Nature Forum</a>, Keynote, AI and Learning from nature
        </li>
        <li>
          2019 January ILLC Course,<a href="https://msclogic.illc.uva.nl/current-students/courses/projects/project/154/1st-Semester-2018-19-Introduction-to-Philosophy-of-Information-">Introduction to Philosophy of Information.&nbsp;</a>
          <br><a title="SEP Information" href="assets/pdfdoc/documents/information.pdf">Handout Stanford Encyclopedia</a>
        </li>
        <li>
          2017 January ILLC Course, <a title="measuring Information in Large Data sets" href="/science/talks-keynotes-workshops-lectures/measuring-information-in-very-large-data-sets.html">Measuring information in large data sets</a>.&nbsp;
        </li>
        <li>
          2014 December 13, <a title="Facticity and Big Data" href="assets/pdfdoc/facticity-and-big-data.pptx">Facticity and Meaningful information</a>,&nbsp;<a title="Keynote Jurix " href="http://conference.jurix.nl/2014/?p=72">Keynote</a>, Jurix conference Krakow
        </li>
        <li>
          2014 November 26, Zien we beeldig of zien we talig?<br><a title="Lezing Instituut voor Beeldtaal" href="http://www.instituutvoorbeeldtaal.nl/evenement/zien-we-beeldig-of-zien-we-talig/">Instituut voor beeldtaal</a>
        </li>
        <li>
          2014 November 17-21&nbsp;<a title="Atlas of Complexity" href="http://www.lorentzcenter.nl/lc/web/2014/662/description.php3?wsid=662&amp;venue=Snellius">What is complexity and how do you measure it</a>. Workshop Lorentz Centre Leiden
        </li>
        <li>
          2014, May 20 ASML Eindhoven, Public lecture: Computers and Creativity.
        </li>
        <li>
          2014 March 23-24, Art, Information and Meaning, McLuhan Invited Lecture and seminar, University of Toronto
        </li>
        <li>
          2013-14 Complexity Conference, Atlas of complexity project.&nbsp;
        </li>
        <li>
          2013 Keynote, September 21, De estetica van Johan Andreas D&egrave;r Mouw&nbsp; in modern perpectief (werktitel), D&egrave;r Mouw Symposium Doetinchem
        </li>
        <li>
          2013 Workshop, April 26, Philosophy of Information Info-Metrics Institute, American University Washington
        </li>
        <li>
          2012 Keynote, December 10, Practical Theories for Exploratory Data Mining (PTDM 2012) </li>
        <li>
          2012 Keynote, November 1, ATIA Conference, Almere, Medical incompleteness theories.
        </li>
        <li>
          2012 Workshop <a href="http://www.mathcomp.leeds.ac.uk/turing2012/WScie12/give-page.php?8">Open Problems in the Philosophy of Information</a>
          <br>Cambridge Turing Centennial Conference.
        </li>
        <li>
          2011 Workshop, Philosophy of Information Info-Metrics Institute, American University Washington
        </li>
        <li>
          2011 <a title="Door schilderijen heen kijken " href="http://www.uitzendinggemist.net/aflevering/73227/Labyrint.html">Door schilderijen heen kijken</a>, VPRO Television, Labyrint, Interview,&nbsp;<a title="Experiment Labyrint" href="http://www.npo.nl/labyrint-site-interview-pieter-adriaans-experiment/19-09-2011/WO_VPRO_035721">Experiment</a>.&nbsp;<a href="http://www.uitzendinggemist.net/aflevering/73227/Labyrint.html">Pieter Adriaans on information and painting</a>,&nbsp;<a href="http://www.uitzendinggemist.net/aflevering/73227/Labyrint.html">Labyrint(VPRO 14 september 2011, in Dutch)</a>
        </li>
        <li>
          2007, <a href="https://youtu.be/HxLkMhSFxmU">Paradiso Lecture 2007 (In Dutch):&nbsp;The work of art as a number</a>
        </li>
        <li>
          2005, The first workshop on philosophy of information
          <ul>
            <li><a title="The world as a computer by Pieter Adriaans " href="http://www.youtube.com/watch?v=3MkAtoGADB4">Opening by Jaap van Den Herik<br /></a></li>
            <li><a title="The world as a computer by Pieter Adriaans " href="http://www.youtube.com/watch?v=YqfoP7q-tuw">The world as a computer by Pieter Adriaans<br /></a></li>
            <li><a title="Remodeling Reality by Tine Wilde" href="http://www.youtube.com/watch?v=w1zWwf0RObs">Does information really exist by Keith Devlin<br /></a></li>
            <li><a title="Remodeling Reality by Tine Wilde" href="http://www.youtube.com/watch?v=FwGTF8KtrVg">Remodeling reality by Tine Wilde<br /></a></li>
            <li><a title="The past and the present of Internet by John McCarthy" href="http://www.youtube.com/watch?v=K13_sWm_gZw">The past and the present of Internet by John McCarthy</a></li>
        </li>
      </ul>
      <li>
        <span>2002, </span><a title="From Knowledge-based Systems to Skill-based Systems: Sailing as a Machine Learning Challenge" href="http://videolectures.net/ecml03_adriaans_fkbs/">From Knowledge-based Systems to Skill-based Systems: Sailing as a Machine Learning Challenge&nbsp;(Dubrovnik 2002)</a>
      </li>

      <li>
        <span>1999, </span> Kwintessens, Pieter Adriaans, Lerende systemen, VPRO Televisie, directed by Ben Jurna, Teleac/NOT. Nominated for best Science documentary, Netherlands Film Festival, 1999. <br> <a href="https://www.filmfestival.nl/film/kwintessens-pieter-adriaans-lerende-systemen">https://www.filmfestival.nl/film/kwintessens-pieter-adriaans-lerende-systemen</a>
      </li>

      </ul>

    </article>

    <article id="projects" class="science-article">

      <h2>Projects under construction</h2>

      <p>In the past years I have worked on a number of projects but published little. Here is an overview of some of the projects:</p>

      <h3>Information theory&nbsp;</h3>

      <ul>
        <li>A General theory of Information and Computation</strong>.&nbsp;</span></span><span>In the coming years I will write down a final summary of some of the research projects I have been working on for the past 45 years. The a preliminary version of the first report on the interaction between Information and Computation has been published this week on&nbsp;</span><a title="A General Theory of Information and Computation" href="https://arxiv.org/abs/1611.07829">arXiv</a><span>. It offers a new perspective on information theor by treating it as a purely mathematical subject. &nbsp;I'll be teaching a class on part of the material at the&nbsp;</span><a title="ILLC" href="https://www.illc.uva.nl/">ILLC</a><span>&nbsp;in Amsterdam in January and hope to publish a more robust version in a journal later that year.</li><br>
        Some results:
        <ul>
          <li>
            It offers unifying perspective on information theory developing the subject as a purely mathematical theory (so no narratives about systems of messages or strings of symbols etc.). &nbsp
          </li>
          <li>
            It offers a detailed analysis of the &lsquo;flow of information&rsquo; through computational operations: starting with primitive recursion, via elementary arithmetic to polynomial functions and Diophantine equations. So via the MRDP-theorem it is really a general theory about information and computation.
          </li>

          <p>
            The theory leads to many new insights and has interesting applications.</p>

          <li>
            <ol type="1">

              <li>
                Flow of information through more complex recursive functions like addition and multiplication is not trivial. Most striking observation: information efficiency of addition is not associative: depending on how we compute a sum of a set of numbers we loose different amounts of information during the process.&nbsp;<br 2) The theory facilitates a simple information theoretical proof of the Fueter-Polya conjecture: that states that the Cantor pairing function is the only polynomial function that maps N to NxN bijctively.&nbsp; </li>
              <li>
                This make the Cantor pairing function a universal measuring device that allows us to study infinite collections of partitions of the set natural numbers. More specifically: via the Cantor pairing function and its generalization we can split any data set into any other amount of data sets with constant costs.
              </li>
              <li>
                This allows us to measure the information in multi dimensional data sets like database tables directly without any conversion costs.
              </li>
              <li>
                On the down side it shows us that there us no hope of finding an optimal split of a data set in a model part and an ad hoc part: In any theory that contains elementary arithmetic these splits come for free. So two-part code optimization does not work for computational theories rich enough to model elementary arithmetic. This point will be addressed in depth in another paper.
              </li>
              <li>
                Using the Cantor function we can describe a polynomial time computable bijection between the set of finite sets of natural numbers and N itself.&nbsp; Thus we have an objective estimate of information in sets of numbers.
              </li>
              <li>
                Point 3) and 6) give us a tool to measure the information in dimensionless objects like large graphs directly without any conversion costs.
              </li>
              <li>
                &nbsp; Combining point 1) and 6) we prove that there is no polynomial function that orders the set of finite sets of numbers on sums: i.e. on can search systematically search for &lsquo;the n-th set of size k&rsquo; but not systematically for the &lsquo;n-th set that adds up to k&rsquo;.
              </li>

            </ol>

          </li>

          <li>
            <span><strong>Time Symmetry in Turing Machines. </strong>Just like in physics in Information theory it makes sense to study systems that live in negative time. I wrote a nice paper about this with Peter van Emde Boas. The work needs to be extended to a full overview of determnistic and non-deterministic computational architectures in positive and negative time. There appears to bee a whole <a href="assets/pdfdoc/papers-under-construction-/kolmogorovs-automata-english.docx">Zoo of systems with different time symmetries</a>. Here is a <a href="assets/pdfdoc/papers-under-construction-/taxonomy-of-turing-machines.pptx">sketch of a taxonomy</a>.&nbsp;</span>
          </li>
          <li>
            <span><strong>Meaningful Information and Creative Systems</strong>. I'm currently working on a&nbsp;</span><a title="Useful information " href="http://plato.stanford.edu/entries/information/supplement.html">computational theory of meaning</a><span>. I discovered that two-part code optimization is not a good technique for separating data in systems that contain elementary arithmatical functions (See the paper on Informartion and Computation:&nbsp;<a title="A General Theory of Information and Computation" href="https://arxiv.org/abs/1611.07829">arXiv</a>). So that part of the paper has to be rewritten. The idea is to develop a formal theory of creativity on the basis of this work and apply this to human cognition. See also my work on&nbsp;</span><a title="Schilderen voor het brein" href="http://www.schilderenvoorhetbrein.nl">painting</a><span>&nbsp;and visual information. Here is the <a href="assets/pdfdoc/papers-under-construction-/a-computational-theory-of-meaning.pdf">Draft paper.&nbsp;</a></span>
          </li>
          <li>
            <span><strong>Information Conservation Principle.&nbsp;</strong>Together with Amos Golan I investigated standard prefix-free Kolmogorov complexity in the context of Zellner&rsquo;s information conservation principle (ICP). We showed that prefix-free Kolmogorov complexity K is not efficient in this sense and proposed a generalized version of Kolmogorov complexity. The paper was accepted at Cie 2014 but I'm not completely happy with the result so I decided to retract it. </span><a href="assets/pdfdoc/papers-under-construction-/generalized-kolmogorov-complexity.pdf">Here is the draft.</a>&nbsp;In the mean time I have discovered the General Law of Conservation of Information (See the paper on Information and Computation&nbsp;<a title="A General Theory of Information and Computation" href="https://arxiv.org/abs/1611.07829">arXiv</a>). So the paper has to be rewritten.&nbsp;
          </li>
          <li>
            <span><strong>Learning by Eaxample.&nbsp;</strong>In order to solve the problems with the previous paper I developed a theory about learning by example: the meaning of an object is the complexity of the simplest &nbsp;example that identifies the object in a certain context. I was able to prove that this theory satisfies Zellners criterion under some strong conditions. I'll probably merge the two papers at some point in time. </span><a href="assets/pdfdoc/papers-under-construction-/informing-by-example.pdf">Here is the draft. &nbsp;</a>
          </li>
          <li>
            <strong>Storing information in a Computational Mechanism. &nbsp;</strong>An investigation into the cost of storing data in a one tape Turing machine. It turns out to be superlinear under mild assumptions. The paper was accepted at Cie 2015 but I'm still not happy with it so I decided to retract it. <a href="assets/pdfdoc/papers-under-construction-/chasm.pdf">Here is the paper.</a>&nbsp;It does allow me to estimate the computing power of various computational models, so in that respect it will be a building block of the theory of meaning project described above.&nbsp;
          </li>
        </ul>

        <h3>Philosophy</h3>
        <ul>

          <li>
            <span><strong>Johan Andreas dėr Mouw.</strong>&nbsp;The Dutch Poet\Philosopher Johan Andreas dėr Mouw (1863-1919) has been a life long inspiration for me. For the 2014 dėr Mouw symposium I wrote a philosphical analysis of his esthetics.&nbsp;</span><a href="assets/pdfdoc/papers-under-construction-/der-mouw-final.pdf">Here is the paper</a><span>(in Dutch). Inspired by dėr Mouws notion of absolute idealism I have had plans to write a monographs on the history of Solipisism for years.
              <a href="assets/pdfdoc/papers-under-construction-/notes-on-solipsis-english.pdf">Here are some notes.&nbsp;</a></span>
          </li>

        </ul>

        <h4>Current Collaborative Projects</span></h4>

        <p>
          <a title="Amsterdam Data Science" href="http://amsterdamdatascience.nl">AAA Data Science,</a>&nbsp; Using information theory and complex network theory to understand the structure of very large knowledge graphs, with Frank van Harmelen.&nbsp;
        </p>

        <h4>Recent Collaborative Projects</h4>

        <ul>

          <li>
            Complexity Measures for e-Science (Wp1, Wp2, <a href="http://www.data2semantics.org/">Data2Semantics</a>, P23, Commit) FInished in 2015.&nbsp;
          </li>
          <li>
            Atlas of Complexity Project (Supported by the <a href="http://www.templeton.org/">Templeton Foundation</a>) Finished 2014.
          </li>

        </ul>

    </article>

    <article id="patents" class="science-article">
      <h2>Patents</h2>

      <ul>

        <li>
          Adriaans P.W. and Van Dungen. European Patent no. 1.062 656<br />A method for automatically controlling electronic musical devices by mean of real-time construction and search of a multi-level data structure.
        </li>
        <li>
          Adriaans et al. United States Patent US 6,311,175 B1<br />System and method for generating performance models of complex information technology systems.
        </li>
        <li>
          Adriaans et al. United States Patent US 6,313,390 B1<br />A method for automatically controlling electronic musical devices by means of real-time construction and search of a multi-level data structure.
        </li>
        <li>
          Adriaans et al Unites States Patent US 6,393,387 B1<br />System and method for model mining complex information management systems.
        </li>
      </ul>

    </article>

    <article id="fundamentals" class="science-article">
      <h2>Fundamental problems in the study of Information and Computation</h2>

      <ul>
        <li>
          <strong> Open problems with classification </strong>
          <ul style="list-style: none; margin-bottom: 0.75em">
            <li>
              * = hard work.
            </li>
            <li>
              ** = vital theory seems to be missing.
            </li>
            <li>
              *** = not even properly understood.
            </li>
            <li>
              **** = nobody has a clue.
            </li>
        </li>

      </ul>

      In computer science there is a lack of consensus about what the real successes and what the real open problems are. In this small document I have (for my own entertainment) tried to give an overview from my personal perspective. Names mentioned are just indications. The classification in terms of hardness is of course open for debate. Some computer scientists think the list is a bit too philosophical, some philosophers think it is a bit too technical, so I have probably struck the right balance between the two disciplines. I actually work on problems 1, 3 and 4, on a rainy day I might be thinking about 8 and 9, in periods of extreme hybris I think about 10. Comments and new suggestions are welcome. I will update the list on a regular basis and hope to live to see some of the problems solved.
      </li>

      <li>
        <strong>*The problem of defining meaningful information:</strong> The biggest problem here is that all well-known information measures (specifically Shannon Information and Kolmogorov complexity) assign the highest information content to data sets with the highest entropy. In this sense a television broadcast with only white noise would contain the most meaningful information. This is counter intuitive. I give the problem only one star because there are a lot of proposals for a form of meaningful information that seem to converge. Some proposals: - Sophistication (Koppel 1988, Antunes, Fortnow 2007) - Logical Depth (Bennet 1988) - effective complexity (Gell-Mann, Lloyd 2003) - Meaningful Information (Vitanyi 2003) - Self-dissimilarity (Wolpert, Mcready 2007) - Computational Depth (Antunes et al. 2006) - Facticty (Adriaans 2009, + unpublished recent work with Duncan Foley and David Oliver). In this last proposal I define facticity as the length of the model code in a two-part code variant of Kolmogorov complexity. I have proved that the facticity is 'symmetrical' in the sense that random data sets as well as very compressible data sets have low facticity. This proposal seems to be richer than (and not in conflict with) the other proposals mentioned. It will be a lot of work to compare these concepts and find out if and how they can be mapped on to each other, but for the moment this seems not impossible.
      </li>
      <li>
        <strong>*What is an adequate logic of information? </strong> What is a good logical system (or set of systems) that formalizes our intuitions of the relation between concepts like 'knowing', 'believing' and 'being informed of'. Proposals by: Dretske, Van Benthem, Floridi and others. In principle I do not see why these concepts could not be mapped onto our current landscape of known logics (structural, modal), it just seems to be a lot of work. Also the discrepancies between proposed systems can be analyzed within the 'normal science' framework of existing logics. That's why I consider the problem to be relatively easy.
      </li>
      <li>
        <strong>** Finite versus continuous models of nature </strong>This problem seems to have bothered the development of a unified theory of information and entropy for the last 150 years or so. The central issue is this: the most elegant models of physical systems are based on functions in continuous spaces. In such models almost all points in space carry an infinite amount of information. Yet, the cornerstone of thermodynamics is that a finite amount of space has finite entropy. What is the right way to reconcile these two views? I believe that the answer is hard because it involves deep questions in (philosophy of) mathematics (an intuitionistic versus a more platonic view). The issue is central in some of the more philosophical discussions on the nature of computation and information (Putnam 1988, Searle 1990). The problem is also related to the notion of phase transitions in the description of nature (e.g. thermodynamics versus statistical mechanics) and to the idea of levels of abstraction (Floridi).
      </li>
      <li>
        <strong>** Computation versus thermodynamics</strong>: There is a reasonable understanding of the relationship between Kolmogorov Complexity and Shannon information (Li, Vitanyi, Grunwald, Cover &amp; Thomas), but the unification between the notion of entropy in thermodynamics and Shannon-Kolmogorov information is very incomplete apart from some very ad hoc insights (Landauer bound, Topsoe and Harremoes, Bais and Farmer 2008). What is a computational process from a thermodynamical point of view? What is a thermodynamical process from a computational point of view. Can a thermodynamic theory of computing serve as a theory of non-equilibrium dynamics? I classify this problem as hard because 150 years of research in thermodynamics still leave us a lot of conceptual unclarities in the heart of the theory of thermodynamics itself. It is also not clear how a theory of computation will help us here, although bringing in concepts of theory of computation seems to be promising. Also possible theoretical models could with high probability be corroborated with feasible experiments (e.g. Joule's adiabatic expansion, See Adriaans 2009)
      </li>
      <li>
        <strong>** Classical information versus quantum information </strong> Classical information is measured in bits (an abbreviation of an oxymoron: binary digit). Implementation of bits in nature involves macroscopic physical systems with at least two different stable states and a low energy reversible transition process (i.e. switches, relays, transistors). The most fundamental way to store information in nature on an atomic level involves qubits. The qubit is described by a state vector in a two-level quantum-mechanical system, which is formally equivalent to a two-dimensional vector space over the complex numbers. Quantum algorithms have, in some cases, a fundamentally lower complexity (e.g. Shor's algorithm for factorization of integers.) The exact relation between classical and quantum information is unclear. Part of it has to do with our lack of understanding of quantum mechanics and with the question wether nature is essentially deterministic or not. Originally I wanted to call this problem "Wheelers question" because of the following little story about him: Well, one day I was at the Institute for Advanced Study, and I went to G&ouml;del's office, and there was G&ouml;del. It was winter and G&ouml;del had an electric heater and his legs were wrapped in a blanket. I said, "Professor G&ouml;del, what connection do you see between your incompleteness theorem and Heisenberg's uncertainty principe?" And G&ouml;del got angry and threw me out of his office! (John Archibald Wheeler as told to Gregory Chaitin)" This story also shows that the question is quite deep. Most philosophers would not agree with G&ouml;dels reactions nowadays.
      </li>
      <li>
        <strong>*** Information and the theory of everything:</strong> In the past decennia information seems to have become a vital concept in physics. Seth Lloyd and others (Zuse, Wolfram) have analyzed computational models of various physical systems. The notion of information seems to play a major role in the analysis of black holes (Information paradox, Hawking, Bekenstein). I myself have played with the suggestion that a black hole is not only an extreme compression of matter but also of data in the sense of Kolmogorov complexity (unpublished). Albert Verlinde has proposed a theory in which gravity is analyzed in terms of information. For the moment these models seem to be purely descriptive without any possibility of empirical verification. The problem seems to be fundamentally harder than the previous one. Hence the three stars.
      </li>
      <li>
        <strong>***The Church-Turing Hypothesis. </strong> We know that a lot of formal systems are Turing equivalent (turing machines, recursive functions, lambda calculus, combinatory logic, cellular automata, to name a few). The question is: does this equivalence define the notion of computation. Some authors believe that new notions of computing (interactive computing (Goldin and Wegner 2007), networked computing) falsify (a strong variant of) the thesis. Others contest this result (Fortnow, personal communication). On the other hand Dershowitz and Gurevich (2008) claim to have vindicated the hypothesis. A lot of conceptual clarification seems necessary, but for now it is unclear how one ever could verify the thesis definitively. The discovery of a system in nature that could actually compute more than a turing machine would imply an immediate falsification, but such a device has not been found up till now, and most researchers deem it unlikely that we ever will. Personally I think that the thesis is still open.
      </li>
      <li>
        <strong>***The tradeoff between information, axiomatization and computation.</strong> This problem is relatively unknown and until recently I thought it was reasonably doable, but then I discovered deep connections with problem 10 (unfortunately). This is the reason why I classify the problem as very hard. The central question in recursion theory could be formulated as: How much information does a number contain relative to a certain set of axioms and how long does it take us to prove this. In computer science the same question reads: how complex is a number in relation to some universal Turing machine with a certain functionality and how long does the program run (if it terminates) relative to the functionality of our universal turing machine. The reason for the three stars is that it seems possible to start to develop some theory to solve the problems. Analysis of the nature of the integer complexity function in the theory of Kolmogorov complexity might be a good start. There is also a relation with problem 1 (specifically computational depth).
      </li>
      <li>
        <strong>***Classification of information generating/discarding processes</strong>: The central question is: which kind of processes do create which kind of information. Deterministic processes do not create information since their future is known. Information is by definition only created by processes that have a stochastic component. But such processes do not necessarily create any meaningful information. If we have a theory of meaningful information then we can develop a theory of processes that create meaningful information (game playing, creation, evolution, the stock market). The problem is related to Abramsky's Problem: Does computation create information? The situation here is equal to that of problem 8: The problem is relatively unknown and until recently I thought it was reasonably doable, but then I discovered deep connections with problem 10 (again unfortunately). Also here it seems not impossible to make a beginning with the formation of a theory (See: Adriaans and van Emde Boas 2010, Information, computation and the arrow of time). Hence the three stars. Again there is a relation with problem 1.
      </li>
      <li>
        <strong>****P versus NP?</strong> Can every problem that can be checked efficiently also be solved efficiently by a computer? See <a href="http://www.claymath.org/millennium/P_vs_NP/Official_Problem_Description.pdf">Cooks paper</a> for a good introduction. This problem appears to be very hard, because after about 40 years of research nobody has any idea of where to start. We seem to have only negative results that tell where 'not' to look. There is no research program that I could think of that would add with certainty anything useful to our understanding of this problem. I suspect that a solution to this problem would imply or at least influence the solution for some other problems in this list (problems 1, 3, 7, 8 and 9 specifically).
      </li>
      </ul>

      <ul>
        <li>
          <strong> Reasonable successes (there are many others):</strong>
        </li>
        <ul>
          <li>
            Theory of computability and decidability (Peano, Godel, Turing, Church etc.)
          </li>
          <li>
            Relation between information and probability (Shannon, Fisher, Kullback-Leibler, Solomonov, Chaitin, Kolmogorov, Hutter etc.)
          </li>
          <li>
            Theory of optimal coding (Shannon, Solomonov)
          </li>
          <li>
            A whole load of useful algorithms with a good understanding of their complexity (See e.g. Knuth).
          </li>
          <li>
            Elegant architectures for various database paradigms: Relational, OO, RDF etc.
          </li>
          <li>
            Scalable architectures for large applications (Web, Operating systems etc ).
          </li>
          <li>
            No free lunch theorems (e.g. Wolpert and McReady)
          </li>
          <li>
            Various solutions to variants of the induction problem (Ockam, Bayes, Rissanen, Vapnik-Chervonenkis, Valiant, Gold, Li, Vitanyi, Vereshcha
            gin, Grunwald) - Minimum Description Length (MDL), - randomness deficiency</li>
          <li>
            Unification between Shannon information and Kolmogorov complexity (Li, Vitanyi, Grunwald, Cover &amp; Thomas)
          </li>
        </ul>
      </ul>

    </article>

  </section>

</main>
<script>
  show('main');

        function show(section) {
            const sections = ['main', 'biblio', 'biblioInfo', 'talksEtc', 'projects', 'patents', 'fundamentals'];
            sections.forEach(s => {
                const el = document.getElementById(s);
                if (el) {
                    if (s === section) {
                        el.classList.add('active');
                    } else {
                        el.classList.remove('active');
                    }
                }
            });
        }
 </script>
<script src="showandhide.js" defer></script>



<?= template_footer() ?>