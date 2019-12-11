@extends('layout.layout')

@section('content')

<div class="CVpage">
    <div class="row">
        <div class="hero-unit col-md-6">
            <h1>Rubinchik Ilya - CV</h1>
        </div>
        <div class="col-md-6">
            <img class="me" src="/images/me/Ilya-Rubinchik-s.jpg" />
            <a href="http://ilfate.net">http://ilfate.net</a><br>
            ilfate@gmail.com<br>
            Skype: illidanfate<br>
            Phone: +49 176 72166321<br>
            <a target="_blank" href="/Rubinchik_Ilya-10.12.19.pdf">Download CV</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div>
                <h1>What I do</h1>
                Architect, Develop and Maintain backend applications in form of Monolith app as well as Microservices with REST APIs.<br>
                Using SOLID, DDD, KISS, DRY<br>
                Optimizing code for best performance and speed for PHP and JS. Optimizing website for best performance with Mysql, Postgres, Redis, Solr, DynamoDB.<br>
                Working with front end apps with JS, Jquery, React-Redux, typescript, GraphQL.<br>
                Playing role of an a Scrum Master to help team work with Scrum and improve overall team performance.<br>
                Analyzing, planning, estimating and distributing tasks in a Kanban teams.<br>
                Teaching and mentoring new developers to a adapt in the team.<br>
                Interviewing and estimating new candidates (both frontend and backend)<br>
                Working closely with PMs to help them create tasks based on needed features for business.<br>
                Doing code reviews and pair programming with other developers.<br>
                Taking responsibilities for project from the infrastructure to the last line of code.<br>
                Maintaining my impeccable sense of humor.<br>
            </div>
            <div>
                <h1>Languages</h1>
                Russian (Mother tongue)<br>
                English (fluent)<br>
                German (basic)
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
                Languages: <b>PHP</b>, <b>JavaScript</b><br>
                Web development: <b>CSS</b>, HTML/XHTML, React-Redux, Jquery, Vue.js, npm, Bootstrap, Angular, Grunt, Scss<br>
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
                Boardgames<br>
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
                November 2010 - June 2011.<br>
                My first Php + Canvas game. Main purpose of creating this game was to learn HTML5-Canvas and increase my PHP skills<br>
                You can find animation demo and information at the page below <a href="{{ action('CodeController@robotRock') }}" >http://ilfate.net/RobotRock</a>
                <h3>Ilfate framework</h3>
                October 2012 - January 2013.<br>
                My PHP microframework. ilfate.net was created with using this framework (migrated to laravel after couple years). The framework was mostly done for fun and to improve skills.<br>
                Github project: <a target="_blank" href="https://github.com/ilfate/ilfate_php_engine" >http://github.com/ilfate/ilfate_php_engine</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="work-experience">
                <h1>Work experience</h1>
                <a target="_blank" class="company-name" href="https://www.olxgroup.com/">OLX Group</a> - Worldwide classifieds brand.<br>
                <h3>Senior Backend Engineer</h3>
                <b>November 2018 - now</b>. Berlin.<br>
                In OLX Group I’m working with several critical parts of our product. My team is working on ensuring the customer’s safety and security by developing automated content moderation solutions. I’m supporting and maintaining features for the old Monolith project(PHP). Extracting parts of it into separate Micro Services(Kotlin). Ensuring integrations across different teams and departments. Supporting critical platform migrations and being on-call for our systems. Working with OKRs and ensuring the quality of overall team results.<br>
                <span class="text-info">PHP + Kotlin + Spring + MySql + AWS (Kinesis + Cognito + Athena)</span>
                <br><br>
                <a target="_blank" class="company-name" href="http://www.getnow.de">GetNow</a> - E-Commerce startup in food delivery.<br>
                <h3>Lead Developer</h3>
                <b>July 2018 - November 2018</b>. Berlin.<br>
                I was hired to build a new development team in Berlin(headquarters are in Munich). I was responsible for searching, processing, interviewing, onboarding new developers to the team. And as well I had to take over a project that was developed by an external team and bring the project to in-house development. I had to take ownership of all parts including back-end front-end and infrastructure very quickly. Unfortunately, the team in Berlin was closed very shortly after I joined.<br>
                <span class="text-info">PHP + Mysql</span>
                <br><br>
                <a target="_blank" class="company-name" href="https://www.audibene.de/">Audibene</a> - Online hearing aids provider.<br>
                <h3>Senior Full Stack Engineer</h3>
                <b>December 2017 - May 2018</b>. Berlin.<br>
                For half a year I joined a team to help with lead processing. I was responsible for REST APIs, SQS queues, communication with SalesForce, Jenkins deployment, production leads recovery, NewRelic dashboards, and front-end speed optimizations.
                <br><br>
                <a target="_blank" class="company-name" href="http://www.watchmaster.com">Watchmaster</a> - E-commerce start-up for luxury watches.<br>
                <h3>Team Lead</h3>
                <b>June 2016 - November 2017</b>. Berlin.<br>
                After I was promoted to be a team lead of the main shop application team, I also got responsibilities to check,
                review, merge and deploy every release that we do for our shop. As well as distribute tasks in my team, plan future releases,
                interview new developers and keep the team spirit!
                <h3>Senior PHP Developer</h3>
                <b>February 2016 - June 2016</b>. Berlin.<br>
                At Watchmaster my responsibilities started with creating an API layer for Solr and implementing the full solr feature
                (Loading products from solr, searching, faceting and etc.) For the most time I was the only one responsible for working with solr.
                My tasks also included development and maintaining payment methods integration such as <a href="http://www.loviit.com/" >FineTrade(Loviit)</a>, <a href="http://www.loviit.com/">V12</a>, <a href="https://www.affirm.com/">Affirm</a>. I also was in charge of website speed optimization and achieved 4 times speed improvement.<br>
                <span class="text-info">PHP(Laravel) + Solr + Postgres + Angular</span>
                <br><br>
                <a target="_blank" class="company-name" href="http://www.nu3.de">Nu3.de</a> - “Your Nutrition Experts: Nutrients & Supplements at nu3”<br>
                <h3>Senior PHP Developer</h3>
                <b>August 2014 - February 2016</b>. Berlin.<br>
                In a scrum team I worked on further development and maintenance of PHP based e-commerce project. Optimization and standardization of system’s architecture. Building separate financial application for Navision integration. Improving mailing.<br>
                <span class="text-info">PHP + Mysql + Apache + Solr</span>
                <br><br>
                <a target="_blank" class="company-name" href="http://www.home24.de">Home24.de</a> - “Germany's biggest online furniture store”<br>
                <h3>Backend PHP Developer</h3>
                <b>April 2013 - August 2014</b>. Berlin.<br>
                I was working in team of 10 backend developers to support and improve successful online store. I was responsible for different parts of the project like: reclamation process, Erp tasks processor, feeds, delta solr indexing and ect. My duties also included bug fixes all over the project, improving performance, improving safety and refactoring old code.<br>
                <span class="text-info">PHP + Mysql + Apache + Solr</span>
                <br><br>
                <a target="_blank" class="company-name" href="http://www.professionali.ru">Professionali.ru</a> - a huge Russian social network for people in
                <h3>PHP Developer</h3>
                professional occupations (like LinkedIn)<br>
                <b>August 2012 - February 2013</b>. Moscow.<br>
                I was developing high load backend application in team of 16 developers. I was responsible for network`s API, some of the network`s apps, creating and supporting different sections of network features, and unitTesting and refactoring parts of project`s core. Here I had my first experience working with Scrum.<br>
                <span class="text-info">PHP + Mysql + Nginx</span>
                <br><br>
                <a target="_blank" class="company-name" href="http://www.ddestiny.ru">Destiny Devopment</a> - A GameDev company that specializes in Browser games<br>
                <h3>Leading Developer</h3>
                <b>Septeber 2011 - August 2012</b>. Moscow<br>
                I was a leading developer in a small team on a browser game project. I created whole project structure and developed most important parts of game logic. I was using MySql + HandlerSocket to improve query speed. I also took a great part in discussing and inventing game design.<br>
                <span class="text-info">PHP + Mysql + Nginx</span>
                <br><br>
                <a target="_blank" class="company-name" href="http://www.prognoz.ru">PROGNOZ</a> - A huge company that fills orders for government and banking<br>
                <h3>Leading Specialist (PHP)</h3>
                <b>August 2010 - September 2011</b>. Moscow<br>
                I was creating and supporting ERP-like systems ordered by Ministry of Health. Those are analytic systems with a lot of complicated real-time analytics and statistics. And also some of them was OLAP-based.<br>
                <span class="text-info">PHP + Oracle + IIS</span>
                <br><br>
                <span class="company-name">M7 Software</span> - A little company that creating internet-shops and personal websites for clients<br>
                <h3>PHP Developer</h3>
                <b>January 2009 - May 2010</b>.(part time job) Moscow<br>
                I was creating sites based on company`s inner framework. This was a part time job where I learned PHP and everything about web development.<br>
                <span class="text-info">PHP + MySql + Apache</span>
            </div>
            
        </div>
    </div>
</div>

@stop

@section('after-content')
    @include('blocks.gdpr')
@stop
