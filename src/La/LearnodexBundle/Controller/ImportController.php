<?php

namespace La\LearnodexBundle\Controller;

use La\CoreBundle\Entity\Action;
use La\CoreBundle\Entity\Affinity;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\AnswerOutcome;
use La\CoreBundle\Entity\ButtonOutcome;
use La\CoreBundle\Entity\HtmlContent;
use La\CoreBundle\Entity\Answer;
use La\CoreBundle\Entity\Persona;
use La\CoreBundle\Entity\SimpleUrlQuestion;
use La\CoreBundle\Entity\Uplink;
use La\CoreBundle\Entity\UrlOutcome;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use La\CoreBundle\Entity\User;

class ImportController extends Controller
{
    private $entityManager;

    public function onderwijsKiezerAction()
    {
        $this->entityManager = $this->getDoctrine()->getManager();
        $this->emptyTheDatabase();

        /** @var $user User */
        $user = $this->get('security.context')->getToken()->getUser();

        $agoraList = array(
            array('name'=>'Professioneel Journalist','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/opleidingen/n/SC_51844932.htm','actions'=>array(
                array('name'=>'Basis Redactioneel werk','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YC0163N.htm','duration'=>'6'),
                array('name'=>'Beeldtaal','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YC0593N.htm','duration'=>'4'),
                array('name'=>'Research 1','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YC0366N.htm','duration'=>'3'),
                array('name'=>'Politieke structuren','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YC0505N.htm','duration'=>'4'),
                array('name'=>'Ethiek','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YC0579N.htm','duration'=>'3'),
                array('name'=>'Frans 1','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YC0552N.htm','duration'=>'3'),
                array('name'=>'Engels 1','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YC0553N.htm','duration'=>'3'),
                array('name'=>'Research 2','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YC0423N.htm','duration'=>'3'),
                array('name'=>'Practicum Print','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YC0507N.htm','duration'=>'3'),
                array('name'=>'Practicum Radio','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YC0508N.htm','duration'=>'3'),
                array('name'=>'Practicum Tv','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YC0509N.htm','duration'=>'3'),
                array('name'=>'Practicum Online','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YC0510N.htm','duration'=>'3'),
                array('name'=>'Economie','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YC0611N.htm','duration'=>'3'),
                array('name'=>'Sociologie','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YC0597N.htm','duration'=>'3'),
                array('name'=>'Frans 2','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YC0554N.htm','duration'=>'3'),
                array('name'=>'Engels 2','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YC0555N.htm','duration'=>'3'),
                array('name'=>'Evoluties in de mediasector','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YC0722N.htm','duration'=>'3'),
                array('name'=>'Media-ontwikkelingen','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YC0723N.htm','duration'=>'4'),
            )),
            array('name'=>'Academisch Biochemicus','url'=>'http://onderwijsaanbod.kuleuven.be/opleidingen/n/SC_51016759.htm','actions'=>array(
                array('name'=>'Algemene en biologische scheikunde','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/E04C9AN.htm','duration'=>'1'),
                array('name'=>'Vergelijkende biologie','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/E05C2AN.htm','duration'=>'5'),
                array('name'=>'Biofysica','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/E04C6BN.htm','duration'=>'8'),
                array('name'=>'Wiskundige methoden voor biomedische wetenschappen','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/E08H1BN.htm','duration'=>'5'),
                array('name'=>'Biochemie en moleculaire biologie','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/E04C1AN.htm','duration'=>'1'),
                array('name'=>'Celbiologie','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/E04C4BN.htm','duration'=>'7'),
                array('name'=>'Anatomie en histologie van het menselijk lichaam','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/E03C9BN.htm','duration'=>'9'),
                array('name'=>'Filosofische reflectie voor biomedische wetenschappen','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/E08F3AN.htm','duration'=>'4'),
            )),
            array('name'=>'Academisch Econoom','url'=>'http://onderwijsaanbod.kuleuven.be/opleidingen/n/SC_51549860.htm','actions'=>array(
                array('name'=>'Hogere Wiskunde I (HIR)','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/D0H10AN.htm','duration'=>'6'),
                array('name'=>'Accountancy (HIR)','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/D0H13AN.htm','duration'=>'6'),
                array('name'=>'Grondslagen van de beleidsinformatica (HIR)','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/D0H17AN.htm','duration'=>'6'),
                array('name'=>'Markten en prijzen','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/D0H48AN.htm','duration'=>'6'),
                array('name'=>'Hogere Wiskunde II (HIR)','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/D0H11AN.htm','duration'=>'6'),
                array('name'=>'Bank- en financiewezen: inleiding tot financiële modellen (HIR)','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/D0H18AN.htm','duration'=>'6'),
                array('name'=>'Marketing (HIR)','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/D0H22AN.htm','duration'=>'6'),
                array('name'=>'De globale economie','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/D0H50AN.htm','duration'=>'6'),
                array('name'=>'Frans I: Algemeen Economisch Frans','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/D0T04AN.htm','duration'=>'3'),
                array('name'=>'Engels I: Algemeen Economisch Engels','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/D0T05AN.htm','duration'=>'3'),
            )),
            array('name'=>'Professioneel Bedrijfskundige','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/opleidingen/n/SC_51844935.htm','actions'=>array(
                array('name'=>'Economie','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YB0205N.htm','duration'=>'4'),
                array('name'=>'Frans 1','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YB0664N.htm','duration'=>'3'),
                array('name'=>'Bedrijfsmanagement','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YB0805N.htm','duration'=>'5'),
                array('name'=>'Integratieproject Bedrijfsmanagement','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YB0806N.htm','duration'=>'3'),
                array('name'=>'Frans 2','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YB0665N.htm','duration'=>'3'),
            )),
            array('name'=>'Academisch Pedagoog','url'=>'http://onderwijsaanbod.kuleuven.be/opleidingen/n/SC_51016918.htm','actions'=>array(
                array('name'=>'Fundamentele wijsbegeerte','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/C00X0AN.htm','duration'=>'6'),
                array('name'=>'Statistiek voor gedragswetenschappers, deel 1','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/P0R97AN.htm','duration'=>'8'),
                array('name'=>'Methoden en technieken van het gedragswetenschappelijk onderzoek: deel 1','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/P0R99AN.htm','duration'=>'6'),
                array('name'=>'Sociologie','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/P0L91AN.htm','duration'=>'4'),
                array('name'=>'Methoden en technieken van het gedragswetenschappelijk onderzoek: deel 2','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/P0S15AN.htm','duration'=>'3'),
                array('name'=>'Psychologie','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/P0S22AN.htm','duration'=>'6'),
                array('name'=>'Geschiedenis van de gedragswetenschappen','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/P0L65AN.htm','duration'=>'5'),
                array('name'=>'Gedragsneurowetenschappen','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/P0L95BN.htm','duration'=>'6'),
                array('name'=>'Ontwikkelingspsychologie','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/P0L93BN.htm','duration'=>'6'),
                array('name'=>'Instructiepsychologie en -technologie','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/P0L72AN.htm','duration'=>'7'),
                array('name'=>'Beleid en organisatie in het onderwijs','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/P0R54AN.htm','duration'=>'5'),
            )),
            array('name'=>'Professioneel Leraar','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/opleidingen/n/SC_51844946.htm','actions'=>array(
                array('name'=>'Didactisch ontwerpen 1: algemene didactiek','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YV0406N.htm','duration'=>'3'),
                array('name'=>'Religie, zingeving en levensbeschouwing - Sociaal & cultureel engagement 1','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YV0410N.htm','duration'=>'3'),
                array('name'=>'Academisch communiceren','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YV0687N.htm','duration'=>'3'),
                array('name'=>'Basisvaardigheden van de leerkracht','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YV0759N.htm','duration'=>'3'),
                array('name'=>'Didactisch atelier','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YV0778N.htm','duration'=>'3'),
                array('name'=>'Didactisch ontwerpen 2: onderwijsleeractiviteiten & lessen','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YV0408N.htm','duration'=>'3'),
                array('name'=>'Brede zorg op school','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YV0409N.htm','duration'=>'3'),
                array('name'=>'Religie, zingeving en levensbeschouwing - Sociaal & cultureel engagement 2','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YV0411N.htm','duration'=>'3'),
                array('name'=>'Communiceren in een onderwijscontext','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YV0688N.htm','duration'=>'3'),
                array('name'=>'Kennismakingsstage','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YV0779N.htm','duration'=>'3'),
            )),
            array('name'=>'Professioneel Ontwerper','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/opleidingen/n/SC_51844895.htm','actions'=>array(
                array('name'=>'Toegepaste wiskunde - basis','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YT0597N.htm','duration'=>'3'),
                array('name'=>'Toegepaste wiskunde - uitbreiding','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YT0598N.htm','duration'=>'3'),
                array('name'=>'Informatietechnologie','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YT0599N.htm','duration'=>'3'),
                array('name'=>'Mechanica','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YT0600N.htm','duration'=>'3'),
                array('name'=>'Sterkteleer 1','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YT0601N.htm','duration'=>'3'),
                array('name'=>'Ontwerptechnieken','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YT0602N.htm','duration'=>'3'),
                array('name'=>'Productietechnieken','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YT0603N.htm','duration'=>'3'),
                array('name'=>'Elektrotechniek 1 - basis','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YT0604N.htm','duration'=>'3'),
                array('name'=>'Elektrotechniek 1 - uitbreiding','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YT0605N.htm','duration'=>'3'),
                array('name'=>'Fluïdomechanica 1','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YT0553N.htm','duration'=>'6'),
                array('name'=>'Werktuigonderdelen','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YT0606N.htm','duration'=>'3'),
                array('name'=>'Geometrische meettechniek 1','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YT0607N.htm','duration'=>'3'),
                array('name'=>'Industriële productie - basis','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YT0323N.htm','duration'=>'6'),
                array('name'=>'Materiaalkunde 1','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YT0610N.htm','duration'=>'3'),
                array('name'=>'CAD 1 - Part Design','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YT0608N.htm','duration'=>'3'),
                array('name'=>'Sterkteleer 2','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YT0609N.htm','duration'=>'3'),
                array('name'=>'Project 1 (OP)','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YT0324N.htm','duration'=>'6'),
            )),
            array('name'=>'Academisch Politicoloog','url'=>'http://onderwijsaanbod.kuleuven.be/opleidingen/n/SC_51016894.htm','actions'=>array(
                array('name'=>'Communicatiewetenschap','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/S0A22AN.htm','duration'=>'6'),
                array('name'=>'Politicologie','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/S0A21AN.htm','duration'=>'6'),
                array('name'=>'Sociologie','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/S0A20AN.htm','duration'=>'6'),
                array('name'=>'Fundamentele wijsbegeerte','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/S0A13AN.htm','duration'=>'4'),
                array('name'=>'Sociale statistiek, m.i.v. oefeningen','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/S0A17DN.htm','duration'=>'8'),
                array('name'=>'Sociale psychologie','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/S0A14AN.htm','duration'=>'6'),
                array('name'=>'Methoden en technieken van het sociaal-wetenschappelijk onderzoek','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/S0A18AN.htm','duration'=>'6'),
                array('name'=>'Inleiding tot het recht','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/S0A15AN.htm','duration'=>'4'),
                array('name'=>'Geschiedenis van de hedendaagse samenleving','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/S0A16AN.htm','duration'=>'6'),
                array('name'=>'Samenleving: feiten en problemen','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/S0A67AN.htm','duration'=>'4'),
                array('name'=>'Initiatie in de onderzoekspraktijk: politieke wetenschappen en sociologie','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/S0E00AN.htm','duration'=>'4'),
            )),
            array('name'=>'Academisch Ingenieur','url'=>'http://onderwijsaanbod.kuleuven.be/opleidingen/n/SC_51016934.htm','actions'=>array(
                array('name'=>'Algemene en technische scheikunde','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/H01A8AN.htm','duration'=>'7'),
                array('name'=>'Toegepaste mechanica, deel 1','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/H01B0AN.htm','duration'=>'5'),
                array('name'=>'Algemene natuurkunde','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/H01B2AN.htm','duration'=>'7'),
                array('name'=>'Thermodynamica','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/H01B4AN.htm','duration'=>'3'),
                array('name'=>'Inleiding tot de materiaalkunde','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/H01D0AN.htm','duration'=>'3'),
                array('name'=>'Elektrische netwerken','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/H01Z2AN.htm','duration'=>'3'),
                array('name'=>'Methodiek van de informatica','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/H01B6BN.htm','duration'=>'6'),
                array('name'=>'Toegepaste algebra','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/H01A4AN.htm','duration'=>'5'),
                array('name'=>'Analyse, deel 1','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/H01A0BN.htm','duration'=>'6'),
                array('name'=>'Analyse, deel 2','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/H01A2AN.htm','duration'=>'5'),
                array('name'=>'Probleemoplossen en ontwerpen, deel 1','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/H01B9AN.htm','duration'=>'4'),
                array('name'=>'Probleemoplossen en ontwerpen, deel 2','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/H01C2AN.htm','duration'=>'3'),
                array('name'=>'Wijsbegeerte','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/H01C4BN.htm','duration'=>'3'),
            )),
            array('name'=>'Academisch Sportwetenschapper','url'=>'http://onderwijsaanbod.kuleuven.be/opleidingen/n/SC_51899043.htm','actions'=>array(
                array('name'=>'Chemie','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/L08A2BN.htm','duration'=>'4'),
                array('name'=>'Biologie en celbiologie','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/L08A4CN.htm','duration'=>'8'),
                array('name'=>'Fysica en biomechanica','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/L08A0BN.htm','duration'=>'8'),
                array('name'=>'Psychologie','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/L09A7AN.htm','duration'=>'4'),
                array('name'=>'Wijsbegeerte','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/L09A3BN.htm','duration'=>'3'),
                array('name'=>'Sociologie','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/L09A5BN.htm','duration'=>'3'),
                array('name'=>'Gezondheids- en ziekteleer','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/L09A1BN.htm','duration'=>'3'),
                array('name'=>'Functionele anatomie','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/L08A9AN.htm','duration'=>'8'),
                array('name'=>'Overzicht van de kinesiologie','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/L09A8BN.htm','duration'=>'4'),
                array('name'=>'Bewegingsleer in de ritmisch expressieve bewegingsactiviteiten I','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/L03B8AN.htm','duration'=>'3'),
                array('name'=>'Bewegingsleer in de individuele bewegingsactiviteiten I','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/L03B1AN.htm','duration'=>'7'),
                array('name'=>'Bewegingsleer in de interactieve bewegingsactiviteiten I','url'=>'http://onderwijsaanbod.kuleuven.be/syllabi/n/L02B8BN.htm','duration'=>'5'),
            )),
            array('name'=>'Professionele ICT','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/opleidingen/n/SC_51844890.htm','actions'=>array(
                array('name'=>'Web engineering 1','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YT0292N.htm','duration'=>'6'),
                array('name'=>'C','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YT0662N.htm','duration'=>'6'),
                array('name'=>'Microcontrollers','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YT0667N.htm','duration'=>'3'),
                array('name'=>'Professionele ontwikkeling','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YT0663N.htm','duration'=>'3'),
            )),
            array('name'=>'Professioneel Verpleger','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/opleidingen/n/SC_51844934.htm','actions'=>array(
                array('name'=>'Theorie van de VPK1 en WOZ1','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YG0172N.htm','duration'=>'3'),
                array('name'=>'Verpleegkundige diagnostiek en interventies 1','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YG0443N.htm','duration'=>'5'),
                array('name'=>'Verpleegkundige vaardigheden','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YG0173N.htm','duration'=>'8'),
                array('name'=>'Verpleegkundige diagnostiek en interventies 2','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YG0005N.htm','duration'=>'6'),
                array('name'=>'Recht','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YG0449N.htm','duration'=>'3'),
                array('name'=>'Preventie 1','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YG0175N.htm','duration'=>'3'),
                array('name'=>'Communicatie 1','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YG0444N.htm','duration'=>'3'),
                array('name'=>'Psychologie','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YG0536N.htm','duration'=>'3'),
                array('name'=>'Wijsbegeerte','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YG0537N.htm','duration'=>'3'),
                array('name'=>'Voedings- en dieetleer','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YG0446N.htm','duration'=>'3'),
                array('name'=>'Anatomie, fysiologie en pathologie 1','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YG0445N.htm','duration'=>'4'),
                array('name'=>'Anatomie, fysiologie en pathologie 2','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YG0447N.htm','duration'=>'3'),
                array('name'=>'Microbiologie en hygiëne','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YG0448N.htm','duration'=>'3'),
                array('name'=>'Stages algemene verpleegkunde 1','url'=>'http://onderwijsaanbodmechelenantwerpen.thomasmore.be/syllabi/n/YG0264N.htm','duration'=>'1'),
            )),

        );
        foreach ($agoraList as $agoraData)
        {
            $agora = $this->createAgora($user,$agoraData['name'],'Meer informatie op <a href="'.$agoraData['url'].'" target="_new">'.$agoraData['url'].'</a>');

            foreach ($agoraData['actions'] as $actionData)
            {
                $action = $this->createAction($user,$actionData['name'],30,'Verken de inhoud van dit vak via haar webpagina.',$actionData['url'],'Wat vind je van dit vak?','Ik ben zeker geïnteresseerd om zoiets te volgen.',100,'Dit zou iets voor mij kunnen zijn, maar ik ben niet zeker.',40);
                $this->createUplink($agora,$action,$actionData['duration']);
            }
        }

        die('done');

    }

    public function newDatabaseAction() {
        $this->entityManager = $this->getDoctrine()->getManager();

        $this->emptyTheDatabase();

        /** @var $user User */
        $user = $this->get('security.context')->getToken()->getUser();

        $agora1 = $this->createAgora($user,'Agora1 - Math','this is the content');
        $agora2 = $this->createAgora($user,'Agora2 - Physics','this is the content');
        $agora3 = $this->createAgora($user,'Agora3 - Economy','this is the content');
        $agora4 = $this->createAgora($user,'Agora4 - Piano','this is the content');
        $agora5 = $this->createAgora($user,'Agora5 - Something Boring','this is the content');
        $agora6 = $this->createAgora($user,'Agora6 - Something Interesting','this is the content');
        foreach (array($agora1,$agora2,$agora3,$agora4,$agora5,$agora6) as $agora) {
            for ($i = 1; $i<5; $i++) {
                $action = $this->createAction($user,'Action'.$i.' For '.$agora->getName(),15,'read and answer','http://www.google.be','select a','a',100,'b',40);
                $this->createUplink($agora,$action,5);
            }
        }
        $persona1 = $this->createPersona("Mathematician");
        $this->setAffinity($persona1->getUser(),$agora1,100);
        $persona2 = $this->createPersona("Physics");
        $this->setAffinity($persona2->getUser(),$agora2,100);
        $persona3 = $this->createPersona("Economist");
        $this->setAffinity($persona3->getUser(),$agora3,100);
        $persona4 = $this->createPersona("Piano player");
        $this->setAffinity($persona4->getUser(),$agora4,100);
        $persona5 = $this->createPersona("Boring Job");
        $this->setAffinity($persona5->getUser(),$agora3,100);
        $this->setAffinity($persona5->getUser(),$agora5,100);
        $persona6 = $this->createPersona("Musician");
        $this->setAffinity($persona6->getUser(),$agora4,100);
        $this->setAffinity($persona6->getUser(),$agora6,100);

        die("done");
    }


    private function emptyTheDatabase() {
        $entities = $this->entityManager->getRepository('LaCoreBundle:Trace')->findAll();
        foreach($entities as $entity) {
            $this->entityManager->remove($entity);
        }
        $uplinks = $this->entityManager->getRepository('LaCoreBundle:Uplink')->findAll();
        foreach($uplinks as $uplink) {
            $this->entityManager->remove($uplink);
        }
        $entities = $this->entityManager->getRepository('LaCoreBundle:Outcome')->findAll();
        foreach($entities as $entity) {
            $this->entityManager->remove($entity);
        }
        $entities = $this->entityManager->getRepository('LaCoreBundle:Affinity')->findAll();
        foreach($entities as $entity) {
            $this->entityManager->remove($entity);
        }
        $entities = $this->entityManager->getRepository('LaCoreBundle:Progress')->findAll();
        foreach($entities as $entity) {
            $this->entityManager->remove($entity);
        }
        $entities = $this->entityManager->getRepository('LaCoreBundle:PersonaMatch')->findAll();
        foreach($entities as $entity) {
            $this->entityManager->remove($entity);
        }
        $entities = $this->entityManager->getRepository('LaCoreBundle:Goal')->findAll();
        foreach($entities as $entity) {
            $this->entityManager->remove($entity);
        }
        $entities = $this->entityManager->getRepository('LaCoreBundle:Answer')->findAll();
        foreach($entities as $entity) {
            $this->entityManager->remove($entity);
        }
        $entities = $this->entityManager->getRepository('LaCoreBundle:LearningEntity')->findAll();
        foreach($entities as $entity) {
            $this->entityManager->remove($entity);
        }
        $entities = $this->entityManager->getRepository('LaCoreBundle:Content')->findAll();
        foreach($entities as $entity) {
            $this->entityManager->remove($entity);
        }

        $entities = $this->entityManager->getRepository('LaCoreBundle:Persona')->findAll();
        foreach($entities as $entity) {
            $this->entityManager->remove($entity->getUser());
            $this->entityManager->remove($entity);
        }

        $this->entityManager->flush();
    }
    private function createAgora($owner,$name,$contentString)
    {
        $agora = new Agora();
        $agora->setName($name);
        $agora->setOwner($owner);
        $content = new HtmlContent();
        $content->setContent($contentString);
        $agora->setContent($content);
        $this->entityManager->persist($content);
        $this->entityManager->persist($agora);
        $this->entityManager->flush();

        echo "created Agora $name<br>";
        return $agora;
    }
    private function createAction($owner,$name,$duration,$instruction,$url,$question,$answer1,$affinity1,$answer2,$affinity2){
        $action = new Action();
        $action->setName($name);
        $content = new SimpleUrlQuestion();
        $content->setDuration($duration);
        $content->setInstruction($instruction);
        $content->setUrl($url);
        $content->setQuestion($question);

        $buttonOutcome1 = new ButtonOutcome();
        $buttonOutcome1->setCaption('DISCARD');
        $buttonOutcome1->setAffinity(0);
        $buttonOutcome1->setLearningEntity($action);

        $buttonOutcome2 = new ButtonOutcome();
        $buttonOutcome2->setCaption('LATER');
        $buttonOutcome2->setAffinity(40);
        $buttonOutcome2->setLearningEntity($action);

        $urlOutcome = new UrlOutcome();
        $urlOutcome->setAffinity(60);
        $urlOutcome->setLearningEntity($action);

        $outcome1 = new AnswerOutcome();
        $a1 = new Answer();
        $a1->setAnswer($answer1);
        $a1->setQuestion($content);
        $content->addAnswer($a1);
        $outcome1->setAnswer($a1);
        $outcome1->setAffinity($affinity1);
        $outcome1->setSelected(1);
        $outcome1->setLearningEntity($action);

        $outcome2 = new AnswerOutcome();
        $a2 = new Answer();
        $a2->setAnswer($answer2);
        $a2->setQuestion($content);
        $content->addAnswer($a2);
        $outcome2->setAnswer($a2);
        $outcome2->setAffinity($affinity2);
        $outcome2->setSelected(1);
        $outcome2->setLearningEntity($action);

        $action->setContent($content);
        $action->setOwner($owner);

        $this->entityManager->persist($content);

        $this->entityManager->persist($a1);
        $this->entityManager->persist($outcome1);

        $this->entityManager->persist($a2);
        $this->entityManager->persist($outcome2);

        $this->entityManager->persist($buttonOutcome1);
        $this->entityManager->persist($buttonOutcome2);
        $this->entityManager->persist($urlOutcome);

        $this->entityManager->persist($action);

        echo "saved action ".$action->getId()."<br>";
        return $action;
    }
    private function createUplink($parent,$child,$weight) {
        $upLink = new Uplink();
        $upLink->setParent($parent);
        $upLink->setChild($child);
        $upLink->setWeight($weight);

        $this->entityManager->persist($upLink);
        $this->entityManager->flush();

        echo "saved uplink ".$upLink->getId()."<br>";
    }
    private function createPersona($userName) {
        /* @var $persona Persona */
        /* @var $user User */
        $persona = new Persona();
        $user = new User();
        $user->setUsername($userName);
        $user->setEmail($userName);
        $user->setPassword('none');
        $user->setEnabled(false);
        $user->setLastLogin(new \DateTime(date('Y-m-d H:i:s',time())));
        $persona->setUser($user);
        $persona->setDescription($userName);
        $this->entityManager->persist($user);
        $this->entityManager->persist($persona);
        $this->entityManager->flush();

        echo "saved persona ".$userName."<br>";
        return $persona;
    }
    private Function setAffinity($user,$agora,$affinityValue) {
        $affinity = new Affinity();
        $affinity->setUser($user);
        $affinity->setAgora($agora);
        $affinity->setValue($affinityValue);
        $this->entityManager->persist($affinity);
        $this->entityManager->flush();
        echo "Affinity $affinityValue for user ".$user->getId()." for agora ".$agora->getId()."<br>";
    }

}
