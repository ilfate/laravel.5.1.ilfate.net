<?php
/**
 * TODO: Package description.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @copyright 2016 Watchmaster GmbH
 * @license   Proprietary license.
 * @link      http://www.watchmaster.de
 */
namespace Ilfate\ShipAi;
use Ilfate\MageSurvival\ChanceHelper;


/**
 * TODO: Short description.
 * TODO: Long description here.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @link      http://ilfate.net
 */
abstract class NameGenerator
{
    protected static $starNames = [
        'Alpha-V', 'Alpha-V', 'Alpha-C', 'Alpha-G', 'Alpha-G', 'Alpha-L', 'Alpha-L', 'Alpha-L', 'Alpaca', 'Aios Chen',
        'Abon', 'Azion', 'Astori', 'Asapolis', 'Akropolis', 'Asgard', 'Asgard', 'Asios Tol', 'Arminis', 'Arotolis',
        'Askaban', 'Armitol', 'Asipolis', 'Azratis', 'Achoris', 'Agard', 'Akillok', 'Aviog', 'Arisamd', 'Arodolis',
        'Akaris', 'Ara Vito', 'Ara Satis', 'Ara Itolis', 'Aratoro', 'Asdef', 'Aklopet', 'Akil Murat', 'Aios Xang', 

        // B
        'Bartoh', 'Bulbohat', 'BHT Cluster', 'BDL Cluster', 'Bauvar', 'Bquio', 'Bugatov', 'Berlin', 'Berlin',
        'Branderburg', 'Birkenpolis', 'Bigolistos', 'Billas', 'Bilbos', 'Bravoburg', 'Bremen', 'Bronitink', 'Birtolis',
        'Briolinis', 'Bia Xois', 'Buytos Gin', 'Blister Billa', 'Balosin', 'Berkantis', 'Bizopolis', 'Bazaltis', 'Buiz',
        'Bizas', 'Bao Vat', 'Bao Fiop', 'Bao Fidle', 'Bao Saras', 'Bao Dertuos', 'Bausat', 'Baunue', 'Beslop', 'Buigop', 
        'Biskolit', 'Bikago', 'Blorago', 'Bilosadia', 'Brutalia', 'Barloburg', 'Burg Vo Burg', 'Bjiolp', 'Bij Xang', 
        'Bchold', 'Bschule', 'Bsolo',
        // C
        'Colin Fall', 'Calm Ice', 'Cillertal', 'Circa', 'CMF Cluster', 'Cyrt', 'Cmon', 'Chokart', 'Cartoff', 'Calif',
        'California', 'California', 'Calosat', 'Colovart', 'Colisopolis', 'Closatras', 'Clobinas', 'Closiburg', 'CiaG',
        'Cartos', 'Crisolis', 'Colikand', 'Cruaton', 'Crigos', 'Chison', 'Chirat', 'Chrosima', 'Chasopolis', 'ChosiX',
        // D
        'Deutchland', 'Das Star', 'Dionis', 'Divola', 'Divora', 'Dallas', 'Demon Star', 'Daemon', 'Diva', 'Dromega',
        'Drepis', 'Dwalin', 'Dwornik', 'Dmitrii', 'Dorovan', 'Doratin', 'Dramastan', 'Drigo Val', 'Drogo', 'Dorimos',
        'Doritos', 'Darvin', 'Darvin', 'Difiburg', 'Drowelt', 'Dvalinstol', 'Divan', 'Dristan', 'Dopamingos', 'Di Va',
        // E
        'Eron', 'Eros', 'Ertat saat', 'Eet', 'Eot Io', 'Emortus', 'Ekaf', 'Eva', 'Eva', 'Etapus', 'Eropolis',
        'Edolis', 'Erzartstan', 'Erevan', 'Efanis', 'Eui Xoa', 'Etan Misk', 'Esipolis', 'Ecostan', 'Eriosis', 'Eroton',
        'Ecorgi', 'Easert', 'Eggburg', 'Ertustan', 'Erzatz', 'Edosill', 'Efrog', 'Erfogo', 'Esqiop', 'Ergioh',
        'Eko Vlass', 'Eko Gross', 'Ertu Vaslid', 'Emu Claad', 'Erg Ort', 'Erg Imb', 'Erg Ora', 'Epitaf', 'Enox',
        'Erhol', 'Edosil', 'Ebipolis', 'Eveop', 'Eio Xang', 'Ewser', 'Edasxio',
        // F
        'Fungi Minimus', 'Farmas', 'Fort North', 'Fort East', 'Fort Mirror', 'Fort Na', 'Fictous', 'Fkalp', 'Ferrus',
        'Faraonisch', 'Figalisis', 'Fo Xang', 'Fia Sio', 'Fo Litos', 'Fatinolis', 'Forkburg', 'Forlisowelt', 'Ferril',
        'Figastan', 'Fisastan', 'Fizalis', 'Fidosipolis', 'Farmasit', 'Frokis', 'Fidolis', 'Fira Dosi', 'Fiowood',
        'Fizotris', 'Florastan', 'Fartasilis', 'Fibolt',
        // G
        'Germany', 'Gedls', 'Gideon', 'G-tech', 'Galp Fiction', 'Golfort', 'Gibraltar', 'Gvont', 'Gostor', 'GSA', 'Goa',
        'Gpol', 'Ghofolk', 'Gmail', 'Gratis', 'Grossia', 'Genova', 'Glister', 'Gipod', 'Gtastan', 'Grossburg', 'Galvan',
        'Gao Fog', 'Gao Ada', 'Gao Dom', 'Goimeg', 'Givas', 'Grillostan', 'Gvegas', 'Gfiol', 'Gosipal', 'Gvertos',
        'Gimalis', 'Gi Oplor', 'Ger Gias', 'Ger Toll', 'Ger Dass', 'Goliker', 'Golander', 'Golivag', 'Golister',
        'Go Volta', 'Go Berl', 'Givivan', 'Gio Chen', 'Gio Chui',
        // H
        'Hot Spot', 'Hot Fa', 'Hot Star', 'Hot Star', 'Hipster', 'Hipstash', 'Halpolk', 'Hamon', 'Habor', 'Hizopl',
        'Hit Sass', 'Hwer', 'Hlovatt', 'Huevorot', 'Hoxart', 'Horde', 'Hymera', 'H Omega', 'H Almint',
        'Halumin', 'Hibis', 'Havak', 'Higrolop', 'Hia Xang', 'Hia Chi', 'Hia Iort', 'Hiasent', 'Hqert', 'Hoplenos',
        'Holistan', 'Hipsteburg', 'Hurgio', 'Harado', 'Hijo', 'Hilazit', 'Hasdimat', 'Havoksburg', 'Hutiop', 'Hlotio',
        'Hlozat', 'Hades', 'Hawopits', 'Hlodofio',
        // I
        'Iova', 'Ivana', 'Igor', 'Itar', 'Ikar', 'Ilya', 'IGTB', 'IGTB', 'IGTB', 'IGTB', 'Igox', 'Idron',
        'Idrasil', 'Iesekil', 'Iskander', 'Ikea', 'Ikalprovon', 'Imastan', 'Iso Burn', 'Iga Valar', 'Inostop',
        'Itta Vasr', 'Itta Drom', 'Itta Gmool', 'Ipolk', 'Ituo', 'Iosotrop', 'Igolp', 'Imnolop', 'Ivisir', 'Isgorod',
        'Ischgl', 'Idwert', 'Ischcago', 'Ilfate', 'Illidan', 'Illinois', 'Imrapen', 'Ifdes', 'Ivo Tigg', 'Ivo Dolp',
        'Ivo Drassur', 'Ivo Kagal', 'Itimal', 'Intosim',
        // J
        'Jimbo', 'J Omega', 'Jobless', 'Jiber', 'JKO', 'JDR', 'Jess', 'Jronimo', 'Jderr', 'Jami Con', 'Jira', 'Jira',
        'Jedai Nien', 'Jello Job',  
        // K
        'Killrog', 'Kirill', 'Kolg Mat', 'Kolg Swat', 'Kopi Drap', 'Kampiroster', 'KDJ', 'Korsar', 'Kimless', 'Kidfar',
        'Kinder', 'Konig', 'Konig', 'Konig', 'Kopengagen', 'Kalp Warp', 'Kelpp Kepler', 'Kvass',
        // L
        'Low Blow', 'Lass', 'Lass', 'Larsloch', 'Larissa', 'Liro', 'Lira', 'Lidro', 'Life of Pedro', 'Limo', 'Livan',
        'Lovitt', 'Loter', 'Lotr', 'Lodvan', 'Lerat', 'Ler', 'LGB', 'Lovap', 'Lator', 'Lram', 'Lvopirdet', 'Lxag',
        'Lbormen', 'Llot',
        // M
        'Mrot', 'Mert', 'Momontom', 'Math Effect', 'Meme Cluster', 'Mistory', 'Massachusetts', 'Moscow', 'Moscow 74',
        'Minsk', 'Minsterous', 'Mikrosass', 'Milk White', 'Mix Mox', 'Mixandr', 'Morales', 'More More', 'Miomore',
        'M Dom', 'M43', 'Mikon', 'Mroak', 'Mkan', 'Mgor', 'Mlon Alox', 'Mlon Alox', 'Mirage X', 'Mirage M',
        'Moonlight', 'Moonstan', 'Moonburg', 'Monomir', 'Mi Sartos', 'Mi Fertos', 'Mi Segol', 'Mibutop', 'Milaras',
        'Mnort', 'Machit', 'Mawopit', 'Masis', 'Ma Butos', 'Ma Vi Fertos', 'Mjong', 'Mezatis', 'Mesantip', 'Mope',
        'Mikolas', 'Muresat', 'Mertpolis', 'Mertburg', 'Mertstan', 'Mjubrtui',
        // N
        'Niko', 'Niko', 'Niko 7', 'Nikon', 'Nikolas', 'Nin', 'Nintendo', 'Nanito', 'New Moscow', 'New Moscow', 
        'New Moscow 3', 'New Berlin', 'New Berlin', 'New Berlin', 'New Berlin 4', 'New Berlin 48', 
        'New Berlin 69', 'New Sartos', 'New London', 'New London', 'New London', 'New London V', 'New London X', 
        'New New York', 'New New York', 'New Warsong', 'New Wiena', 'New Texas', 'New Mexico', 'New Muhosransk', 
        'New Muhosransk', 'New Nova', 'New Nova', 'New Nova X', 'New Nova X', 'New Tokio', 'New Tokio', 
        'New Tokio', 'Nanopolis', 'Niradox', 'Noterdam', 'Note Galaxy X', 'Nyrex', 'NQRD', 'NSQL', 'Nwertobart', 
        'Neverwinter', 'Neverwinter N', 'Nikopolis', 'Nan-o-Nan', 'Nerkost', 'Namastet', 'Nerv', 'Nuijot',
        'Niska', 'Nikolaps', 'Nanostan', 'Nervoburg', 'Natris', 'NR Vita', 'Nas Riva', 'Noctural', 'Naropolis', 
        'Nitrostan', 'Nutros', 'Nawelt', 'Ni Vilot', 'Nui Xent', 'Nyi Wio',  
        // O
        'Octopolis', 'Okama', 'Orbita', 'Orak', 'Orion', 'Orion X', 'Orion', 'Orion Horizon', 'Odo Maskit', 
        'Odo Tvortag', 'Odo Ramstech', 'Opistar', 'Opoogon', 'Ogiv', 'Obivan', 'Otoslak', 'Ohoardisk', 'Ortobachil', 
        'Owed', 'Oxidon', 'Oxidofrag', 'Oxagon', 'Oxifidol', 'Oxertod', 'Overwatch', 'Okio', 'Okio X', 'Ofar', 'Odsin', 
        'Onixia',
        // P
        'Pertos', 'Pedros', 'Peprasit', 'Parasit', 'Polinol', 'Polin', 'Poland', 'Porama', 'Pornhub', 'Piromanik S', 
        'Piro', 'Piro', 'Piro X', 'Pen Den', 'PenAsil', 'Prosik', 'Prineos', 'Prineos', 'Prineos', 
        'Prikoralis', 'Prikoralis X', 'Prikoralis Minox', 'Prasikan', 'Parkan', 'Park Oslo X', 'Pifogorus', 
        'Primory Reason', 'Post Mtgox', 'Post Bank', 'Pisaka', 'Pmint', 'Polosatik', 'Permanentus', 'Persion', 
        'Persopolis', 'Pastogres', 'Postgres',
        // Q
        'Quora', 'Qikos', 'Query', 'Queue', 'Quebek', 'Quiotr', 'Qvoitrks', 'Quadropolis', 'Qversipolis', 'Qnimostar', 
        'Qni Moss', 'Qni Mitog', 'Qni Maratox', 'Qni Mitarr', 'Qni Mosipolis', 'Qni Medrass', 'Qni Medved', 'Qsosix', 
        'Qxok', 'Quadro Virsalis', 'Quadro Sardinas', 'Qwellis', 'Qberfolk', 'Qovarkis', 'Qzasopos', 'Qvardiburg', 
        'Quill18',
        // R
        'Risoppolis', 'Rawburg', 'Ras Berlin', 'Ras Omsk', 'Ras Paris', 'Raskal', 'Rosopolk', 'Rovaris', 'Rodimis', 
        'Rorisavisburg', 'Roriwelt', 'Roterdam', 'Rimini', 'Rimini', 'Riminisburg', 'Ristalisk', 'Risimonotoris', 
        'Risort', 'Resortimos', 'Rast Wart', 'Rast Warp', 'Rast Folk', 'Rast Fiord', 'Rasimol', 'Rasox', 'Rax Yelpp', 
        'Radoistiol', 'Rimolesk', 'Retrositol', 'Rememberberry', 'Rewrtfield', 'Raskowelt', 'Razer Tosk', 'Razor Bali', 
        'Raptor', 'Raptor', 'Raptor', 'Ralrol', 'Rock Tirol', 'Rimwelt', 'Ravi',
        // S
        'Satis', 'Samora', 'Safitopol', 'Sofiwelt', 'Sofiburg', 'Solitar', 'Saltlake', 'Saltworld', 'Salt Dirm', 
        'Sasilapu', 'Sadilampolis', 'Seporat', 'Scheise', 'Schade', 'Shagor', 'Sharitol Tux', 'Sharitol Dim', 
        'Sharitol Log', 'Sharitol Personis', 'Sodusechol', 'Siberatus', 'Singapore', 'Singapore', 'Singapore', 
        'Singapore XI', 'Solitarburg', 'Solaris', 'Solaris', 'Solaris', 'Solaris', 'Solaris V', 'Solaris VI', 
        'Solaris', 'Solaris X', 'Sol', 'Small Britan', 'Scoundrell', 'Scala', 'Saruman', 'Sauron', 'Saphir', 
        'Sifon', 'Silot', 'Sig Hog', 'Segotol', 'Slandor', 'Slandor', 'Sheri Vi',
        // T
        'Talin', 'Talin', 'Tokio', 'Tokio', 'Tokio FT', 'TK Kongo', 'Takini', 'Tak-m-tak', 'Tarkan', 'Tarakan', 
        'Taworan', 'Tomsk', 'Tori Kioki', 'Tod Dunken', 'Tosimo', 'Tochich', 'Talinsburg', 'Tikoriwelt', 'Tikori Fogi', 
        'Timiber', 'Tix', 'Tix Tux', 'Tvorog', 'Tufogi', 'Tux Hinter', 'Tutor', 'Tuch-o-tech', 'Tom Farrel', 'Tof', 
        'Toffer', 'Trambon', 'Tramp Down', 'Trasser Toss', 'Tchot', 'Terra Nova', 'Terra Insomnia', 'Terra Mistica', 
        'Terra Fierra', 'Terrapolis', 'Tibone', 'Tretta', 'Tetta',
        // Y
        'Yovann', 'Ygoot', 'Ygar', 'Yoozat', 'Ypot', 'Youwalin', 'Ycolit', 'Yerdling', 'Yio Erd', 'Yuip Fredig', 
        'Yesdir Mini', 'Yorik', 'Yoper Wopper', 'Yuifrod', 'Yesnoburg', 'Yasin', 'Yanosik', 'Yanokato', 'Yanokato Shei', 
        'Yanokato Voi', 'Yiusinos', 'Yesodan', 'Yetsumi', 'Yetsumi Misobargo', 'Yerkantim',
        // V
        'Vital', 'Varat', 'Vatican', 'Validol', 'Varatos', 'Vi Consis', 'Vi Chardis', 'Vi Xang', 'Verison', 'Versosin',
        'Vui Giost', 'Valentin', 'Vidas', 'Voudot', 'Vijot', 'Vartos', 'Vertuiop', 'Vertes', 'Vitostan', 'Vartburg',
        'Vaserhof', 'Volta', 'Volpias', 'Vui Xans', 'Vio Volo', 'Vio Tula', 'Valis Kchaal', 'Vales Vale', 'Varus Von',
        'Virten', 'Vijertii', 'Verotram', 'Vigo Halman', 'Varis', 'Vitalisk', 'Volch', 'Volvo Corp', 'Vooglight',
        'Voogstorm', 'Voogitor', 'Voogburg', 'Voog', 'Voog', 'Visatoris', 'Visoks', 'Vi Dilog', 'Vi Askop', 'Vi Fertos',
        // W
        'Waristo', 'Wien', 'Wientol', 'Watwas', 'Waservol', 'Wolit Gun', 'Witor Fid', 'Wistek', 'Wistek Dolsi', 
        'Wafolint', 'Warqos', 'Wasabis', 'Waxholin', 'Waxopolis', 'Wertinburg', 'Welt', 'Wikey', 'Wikloy', 'Wilkens', 
        'Willsburg', 'Willenois', 'Wiskonsin', 'Waterson', 'Waters Deep', 'Widobrass', 'Wiosin', 'Wagrios', 
        'Wertolisis', 'Wherolis', 'Wtuopis', 'Widi Saat', 'Wilo Kid', 'Winko Wassi', 'Wikolis Tirol', 'Wui Tai', 
        'Wio Xang', 'Wui Cheng',
        // X
        'Xenoshift', 'Xotopol', 'Xertol', 'Xenopolis', 'Xenoburg', 'X Wing', 'X Derrot', 'X Sawart', 'X Fertonid', 
        'X Saderut', 'Xi Yosima', 'Xi Xopolis', 'Xi Roden', 'Xi Firtod', 'Xios Vatar', 'Xios Qatar', 'Xios Mittog', 
        'Xios Gerdon', 'Xaos', 'Xistot',
        // Z
        'Zatosi', 'Zinopolis', 'Zertos', 'Zontag', 'Zona 51', 'Zoronis', 'Zarya', 'Zigros', 'Zaart', 'Zio Yoig', 
        'Zio Xang', 'Zio Chan', 'Zigmed', 'Zoo Will', 'Zitaras',

    ];

    protected static $numbers = [
        'II',
        'III',
        'IV',
        'V',
        'VI',
        'VII',
        'VIII',
        'IX',
        'X',
        'XI',
        'XII',
        'XIII',
        'XIV',
        'XV',
        'XVI',
        'XVII',
        'XVIII',
        'XIX',
        'XX',
    ];


    public static function generate($config)
    {
        if (!empty($config['type']) && $config['type'] == 'star-number') {
            return self::starNumber($config);
        }
        if (!empty($config['type']) && $config['type'] == 'star') {
            return self::starName($config);
        }
    }

    public static function starNumber(Star $star)
    {
        $number = '';
        $charactersForNumber1 = 'wrtpsdfghklzxcvbnm';
        for($i = 0; $i < 4; $i++) {
            $number .= $charactersForNumber1[rand(0, strlen($charactersForNumber1) - 1)];
        }
        $number = ucfirst($number);
        $number .= '-';
        switch ($star->type) {
            case Star::TYPE_YELLOW_DWARF:
                $number .= 'YD';
                break;
            case Star::TYPE_O_TYPE:
                $number .= 'O';
                break;
            case Star::TYPE_BLUE_GIANT:
                $number .= 'B';
                break;
            case Star::TYPE_A_TYPE:
                $number .= 'A';
                break;
            case Star::TYPE_F_TYPE:
                $number .= 'F';
                break;
            case Star::TYPE_K_TYPE:
                $number .= 'K';
                break;
            case Star::TYPE_M_TYPE:
                $number .= 'M';
                break;
            default:
                $number .= 'M';
                break;
        }
        $number .= '-';
        $number .= rand(10, 9999);
        return $number;
    }

    public static function starName($star)
    {
        
        switch ($star->type) {
            case Star::TYPE_YELLOW_DWARF:
            case Star::TYPE_A_TYPE:
            case Star::TYPE_M_TYPE:
                break;
            case Star::TYPE_O_TYPE:
            case Star::TYPE_BLUE_GIANT:
            case Star::TYPE_F_TYPE:
            case Star::TYPE_K_TYPE:
            default:
                return '';
                break;
        }
        $nameOrigin = ChanceHelper::oneFromArray(self::$starNames);
        $name = $nameOrigin;
        $currentNumber = -1;
        while (true) {
            if (Star::where('name', '=', $name)->where('galaxy_id', '=', $star->galaxy_id)->first()) {
                $currentNumber++;
                if (empty(self::$numbers[$currentNumber])) {
                    $name = $nameOrigin . ' ' . rand(21, 999);
                } else {
                    $name = $nameOrigin . ' ' . self::$numbers[$currentNumber];
                }
            } else {
                break;
            }
        }
        return $name;
    }

    public function planetName($usedNames)
    {
        $nameOrigin = ChanceHelper::oneFromArray(self::$starNames);
        while (in_array($nameOrigin, $usedNames)) {
            $nameOrigin = ChanceHelper::oneFromArray(self::$starNames);
        }
        return $nameOrigin;
    }

}