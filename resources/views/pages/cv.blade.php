@extends('layout.layout')

@section('content')

<div class="CVpage">
    <div class="row">
        <div class="hero-unit col-md-7">
            <h1>Rubinchik Ilya - CV</h1>
        </div>
        <div class="col-md-5">
            <img class="me" src="/images/me/Ilya-Rubinchik-s.jpg" />
            <a href="http://ilfate.net">http://ilfate.net</a><br>
            ilfate@gmail.com<br>
            Skype: illidanfate<br>
            Phone: +49 176 72166321<br>
            Phone: +7 905 7136748<br>
            <a target="_blank" href="/Rubinchik_Ilya.pdf">Download CV</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div>
                <h1>Languages</h1>
                Russian (fluent)<br>
                English (upper-intermediate)
            </div>
            <div>
                <h1>Education</h1>
                Moscow Aircraft Institute (2005-2011)<br>
                Rocket-science engineer (specialty: nano satellites)
            </div>
            <div>
                <h1 class="pull-left">Skills</h1>
                <strong><a class="pull-left like-h1" href="{{ action('PageController@skills') }}">learn more</a></strong>
                <div class="clearfix"></div>
                Languages: <b>PHP</b>, <b>JavaScript</b>, Ruby, Java<br>
                Web development: <b>CSS</b>, HTML/XHTML, Jquery, Vue.js, Bootstrap, Angular, Grunt, Scss, Compas<br>
                DB: <b>MySql</b>, Solr, Postgres, <span class="tip" rel="tooltip" title="Module for MySql to work with it like noSql database" >HandlerSocket</span>, Sphinx, Oracle, Redis, Memcached<br>
                VCS: <b>Git</b>, Svn<br>
                Frameworks: Laravel, ZendFramework<br>
                Other: <b>PHPUnit</b>, Nginx, Vagrant, Saltstack, Behat, Selenium, Phing, Jira, Scrum, AWS<br>
                <a href="{{ action('PageController@skills') }}">My skills table</a>
            </div>
            <div>
                <h1>Certificates</h1>
                <a target="_blank" class="pull-left cv-certificate"  href="http://www.zend.com/en/store/education/certification/yellow-pages.php#show-ClientCandidateID=ZEND021010">
                    <img src="http://www.zend.com/img/yellowpages/zce_php5-3_logo.gif" />
                </a>
                <h4>PHP 5.3 Zend Certified Engineer</h4>
                Certification date: Oct 22nd, 2012<br>
                Zend Certificate page:
                <a target="_blank" href="http://www.zend.com/en/store/education/certification/yellow-pages.php#show-ClientCandidateID=ZEND021010">
                    Ilya Rubinchik
                </a>
            </div>
            <div>
                <h1>Interests</h1>
                Web development<br>
                Game development<br>
                <a target="_blank" href="http://www.youtube.com/watch?v=xk2_qX_oU3U">Snowboarding</a><br>
                Climbing<br>
                Reading<br>
                Traveling<br>
                Bicycling<br>
            </div>
            <div>
                <h1>My social networks pages</h1>
                <a target="_blank" href="http://vk.com/ilfate">Vkontakte</a><br>
                <a target="_blank" href="http://www.facebook.com/profile.php?id=100001037561585">Facebook</a><br>
                <a target="_blank" href="http://www.linkedin.com/pub/ilya-rubinchik/57/777/6b/en">LinkedIn</a><br>
                <a target="_blank" href="https://github.com/ilfate">Github</a><br>
                <a target="_blank" href="https://plus.google.com/u/0/104220186237319355155/posts">Google+</a><br>
            </div>
            <div>
                <h1>Personal Projects</h1>
                <h3>GuessSeries</h3>
                February 2015.<br>
                One more simple JS game made in 2 weeks. This time it is a quiz game about series. In total game was played by 15k players.<br>
                Game: <a href="{{ action('GuessGameController@index') }}" >GuessSeries</a>
                <h3>Math Effect</h3>
                October 2014 - November 2014.<br>
                A simple JS game I made in 2 weeks. Math Effect is a turn-based strategic game. In total game was played by 43k players.<br>
                Game: <a href="{{ action('MathEffectController@index') }}" >Math effect</a>
                <h3>Robot Rock</h3>
                Novemder 2010 - June 2011.<br>
                My first Php + Canvas game. Main purpose of creating this game was to learn HTML5-Canvas and increase my PHP skills<br>
                You can find animation demo and information at the page below <a href="{{ action('CodeController@robotRock') }}" >http://ilfate.net/RobotRock</a>
                <h3>Ilfate framework</h3>
                October 2012 - January 2013.<br>
                My PHP micro-framework. ilfate.net is created with this framework<br>
                Github project: <a target="_blank" href="https://github.com/ilfate/ilfate_php_engine" >http://github.com/ilfate/ilfate_php_engine</a>
            </div>
        </div>
        <div class="col-md-5 col-md-offset-2">
            <div>
                <h1>Work experience</h1>

                <h3>Team Lead</h3>
                <b>June 2016 - now</b>. Berlin.<br>
                <h3>Senior PHP Developer</h3>
                <b>February 2016 - June 2016</b>. Berlin.<br>
                <a target="_blank" class="company-name" href="http://www.watchmaster.com">Watchmaster</a> - “Globally buys and sells luxury watches of all brands”<br>
                At Watchmaster my responsibilities started with creating an API layer for Solr and implementing the full solr feature (Loading products from solr, searching, faceting and etc.) For the most time I was the only one responsible for working with solr. My tasks also included development and maintaning payment methods integration such as <a href="http://www.loviit.com/" >FineTrade(Loviit)</a>, <a href="http://www.loviit.com/">V12</a>, <a href="https://www.affirm.com/">Affirm</a>. After I was promoted to be a team lead of the team that worked on shop application, I also got responsibilities to review, merge and deploy every release that we do for our shop.<br>
                <span class="text-info">PHP(Laravel) + Solr + Postgres + Angular</span>

                <h3>Senior PHP Developer</h3>
                <a target="_blank" class="company-name" href="http://www.nu3.de">Nu3.de</a> - “Your Nutrition Experts: Nutrients & Supplements at nu3”<br>
                <b>January 2015 - February 2016</b>. Berlin.<br>
                In a scrum team I worked on further development and maintenance of PHP based e-commerce project. Optimization and standardization of system’s architecture. Building separate financial application for Navision integration. Improving mailing.<br>
                <span class="text-info">PHP + Mysql + Apache + Solr</span>

                <h3>Backend PHP Developer</h3>
                <a target="_blank" class="company-name" href="http://www.home24.de">Home24.de</a> - “Germany's biggest online furniture store”<br>
                <b>April 2013 - August 2014</b>. Berlin.<br>
                I was working in team of 10 backend developers to support and improve successful online store. I was responsible for different parts of the project like: reclamation process, Erp tasks processor, feeds, delta solr indexing and ect. My duties also included bug fixes all over the project, improving performance, improving safety and refactoring old code.<br>
                <span class="text-info">PHP + Mysql + Apache + Solr</span>

                <h3>PHP Developer</h3>
                <a target="_blank" class="company-name" href="http://www.professionali.ru">Professionali.ru</a> - a huge Russian social network for people in
                professional occupations (like LinkedIn)<br>
                <b>August 2012 - February 2013</b>. Moscow.<br>
                I was developing high load backend application in team of 16 developers. I was responsible for network`s API, some of the network`s apps, creating and supporting different sections of network features, and unitTesting and refactoring parts of project`s core. Here I had my first experience working with Scrum.<br>
                <span class="text-info">PHP + Mysql + Nginx</span>

                <h3>Leading Developer</h3>
                <a target="_blank" class="company-name" href="http://www.ddestiny.ru">Destiny Devopment</a> - A GameDev company that specializes in Browser games<br>
                <b>Septeber 2011 - August 2012</b>. Moscow<br>
                I was a leading developer in a small team on a browser game project. I created whole project structure and developed most important parts of game logic. I was using MySql + HandlerSocket to improve query speed. I also took a great part in discussing and inventing game design.<br>
                <span class="text-info">PHP + Mysql + Nginx</span>

                <h3>Leading Specialist (PHP)</h3>
                <a target="_blank" class="company-name" href="http://www.prognoz.ru">PROGNOZ</a> - A huge company that fills orders for government and banking<br>
                <b>August 2010 - September 2011</b>. Moscow<br>
                I was creating and supporting ERP-like systems ordered by Ministry of Health. Those are analytic systems with a lot of complicated real-time analytics and statistics. And also some of them was OLAP-based.<br>
                <span class="text-info">PHP + Oracle + IIS</span>

                <h3>PHP Developer</h3>
                <span class="company-name">M7 Software</span> - A little company that creating internet-shops and personal websites for clients<br>
                <b>January 2009 - May 2010</b>.(part time job) Moscow<br>
                I was creating sites based on company`s inner framework. This was a part time job where I learned PHP and everything about web development.<br>
                <span class="text-info">PHP + MySql + Apache</span>
            </div>
            
        </div>
    </div>
</div>

@stop
