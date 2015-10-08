<?php

namespace La\LearnodexBundle\Controller;

use La\CoreBundle\Entity\Action;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\AnswerOutcome;
use La\CoreBundle\Entity\ButtonOutcome;
use La\CoreBundle\Entity\HtmlContent;
use La\CoreBundle\Entity\Answer;
use La\CoreBundle\Entity\SimpleUrlQuestion;
use La\CoreBundle\Entity\Techne;
use La\CoreBundle\Entity\Uplink;
use La\CoreBundle\Entity\UrlOutcome;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use La\CoreBundle\Entity\User;

class ImportController extends Controller
{
    private $entityManager;

    public function clearDatabaseAction()
    {
        $this->entityManager = $this->getDoctrine()->getManager();
        $this->emptyTheDatabase();
        return $this->redirect($this->generateUrl('homepage'));
    }

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

        return $this->redirect($this->generateUrl('homepage'));
    }

    public function newDatabase1Action() {
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
            for ($i = 1; $i<9; $i++) {
                $action = $this->createAction($user,'Action'.$i.' For '.$agora->getName(),15,'read and answer','http://www.google.be','select a','a',100,'b',40);
                $this->createUplink($agora,$action,5);
            }
        }

        return $this->redirect($this->generateUrl('homepage'));
    }

    public function newDbBnlearnKeywordsAsSkillsAction() {
        $this->entityManager = $this->getDoctrine()->getManager();
        $this->emptyTheDatabase();

        /** @var $user User */
        $user = $this->get('security.context')->getToken()->getUser();

        $job1 = $this->createTechne($user,'Java developer','');
        $job2 = $this->createTechne($user,'.net developer','');
        $job3 = $this->createTechne($user,'php developer','');
        $job4 = $this->createTechne($user,'marketing manager','');
        $job5 = $this->createTechne($user,'marketing specialist','');
        $job6 = $this->createTechne($user,'marketing assistent','');
        $agora1 = $this->createAgora($user,'Java','');$action1 = $this->createAction($user,'Java',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> Java</b>?','yes',100,'no',0);$this->createUplink($agora1,$action1,5);$this->createUplink($job1,$agora1,37.3);
        $agora2 = $this->createAgora($user,'Javascript','');$action2 = $this->createAction($user,'Javascript',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> Javascript</b>?','yes',100,'no',0);$this->createUplink($agora2,$action2,5);$this->createUplink($job1,$agora2,9.9);
        $agora3 = $this->createAgora($user,'Oracle','');$action3 = $this->createAction($user,'Oracle',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> Oracle</b>?','yes',100,'no',0);$this->createUplink($agora3,$action3,5);$this->createUplink($job1,$agora3,8.5);
        $agora4 = $this->createAgora($user,'CSS','');$action4 = $this->createAction($user,'CSS',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> CSS</b>?','yes',100,'no',0);$this->createUplink($agora4,$action4,5);$this->createUplink($job1,$agora4,6.5);
        $agora5 = $this->createAgora($user,'HTML','');$action5 = $this->createAction($user,'HTML',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> HTML</b>?','yes',100,'no',0);$this->createUplink($agora5,$action5,5);$this->createUplink($job1,$agora5,5.7);
        $agora6 = $this->createAgora($user,'Struts','');$action6 = $this->createAction($user,'Struts',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> Struts</b>?','yes',100,'no',0);$this->createUplink($agora6,$action6,5);$this->createUplink($job1,$agora6,4.9);
        $agora7 = $this->createAgora($user,'jQuery','');$action7 = $this->createAction($user,'jQuery',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> jQuery</b>?','yes',100,'no',0);$this->createUplink($agora7,$action7,5);$this->createUplink($job1,$agora7,3.9);
        $agora8 = $this->createAgora($user,'MVC','');$action8 = $this->createAction($user,'MVC',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> MVC</b>?','yes',100,'no',0);$this->createUplink($agora8,$action8,5);$this->createUplink($job1,$agora8,3.3);
        $agora9 = $this->createAgora($user,'JSF','');$action9 = $this->createAction($user,'JSF',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> JSF</b>?','yes',100,'no',0);$this->createUplink($agora9,$action9,5);$this->createUplink($job1,$agora9,3.1);
        $agora10 = $this->createAgora($user,'Rest','');$action10 = $this->createAction($user,'Rest',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> Rest</b>?','yes',100,'no',0);$this->createUplink($agora10,$action10,5);$this->createUplink($job1,$agora10,2.9);
        $agora11 = $this->createAgora($user,'AngularJS','');$action11 = $this->createAction($user,'AngularJS',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> AngularJS</b>?','yes',100,'no',0);$this->createUplink($agora11,$action11,5);$this->createUplink($job1,$agora11,2.7);
        $agora12 = $this->createAgora($user,'Android','');$action12 = $this->createAction($user,'Android',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> Android</b>?','yes',100,'no',0);$this->createUplink($agora12,$action12,5);$this->createUplink($job1,$agora12,2.1);
        $agora13 = $this->createAgora($user,'MySQL','');$action13 = $this->createAction($user,'MySQL',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> MySQL</b>?','yes',100,'no',0);$this->createUplink($agora13,$action13,5);$this->createUplink($job1,$agora13,1.9);
        $agora14 = $this->createAgora($user,'PostgreSQL','');$action14 = $this->createAction($user,'PostgreSQL',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> PostgreSQL</b>?','yes',100,'no',0);$this->createUplink($agora14,$action14,5);$this->createUplink($job1,$agora14,1.6);
        $agora15 = $this->createAgora($user,'NodeJS','');$action15 = $this->createAction($user,'NodeJS',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> NodeJS</b>?','yes',100,'no',0);$this->createUplink($agora15,$action15,5);$this->createUplink($job1,$agora15,1.5);
        $agora16 = $this->createAgora($user,'Ajax','');$action16 = $this->createAction($user,'Ajax',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> Ajax</b>?','yes',100,'no',0);$this->createUplink($agora16,$action16,5);$this->createUplink($job1,$agora16,1.3);
        $agora17 = $this->createAgora($user,'Spring MVC','');$action17 = $this->createAction($user,'Spring MVC',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> Spring MVC</b>?','yes',100,'no',0);$this->createUplink($agora17,$action17,5);$this->createUplink($job1,$agora17,1.3);
        $agora18 = $this->createAgora($user,'iOS','');$action18 = $this->createAction($user,'iOS',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> iOS</b>?','yes',100,'no',0);$this->createUplink($agora18,$action18,5);$this->createUplink($job1,$agora18,1.2);
        $agora19 = $this->createAgora($user,'Perl','');$action19 = $this->createAction($user,'Perl',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> Perl</b>?','yes',100,'no',0);$this->createUplink($agora19,$action19,5);$this->createUplink($job1,$agora19,0.3);
        $agora20 = $this->createAgora($user,'Drupal','');$action20 = $this->createAction($user,'Drupal',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> Drupal</b>?','yes',100,'no',0);$this->createUplink($agora20,$action20,5);$this->createUplink($job1,$agora20,0.2);
        $agora21 = $this->createAgora($user,'C#','');$action21 = $this->createAction($user,'C#',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> C#</b>?','yes',100,'no',0);$this->createUplink($agora21,$action21,5);$this->createUplink($job2,$agora21,18.7);
        $this->createUplink($job2,$agora8,10.8);
        $this->createUplink($job2,$agora2,8.1);
        $agora22 = $this->createAgora($user,'WPF','');$action22 = $this->createAction($user,'WPF',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> WPF</b>?','yes',100,'no',0);$this->createUplink($agora22,$action22,5);$this->createUplink($job2,$agora22,8);
        $agora23 = $this->createAgora($user,'WCF','');$action23 = $this->createAction($user,'WCF',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> WCF</b>?','yes',100,'no',0);$this->createUplink($agora23,$action23,5);$this->createUplink($job2,$agora23,7.4);
        $this->createUplink($job2,$agora5,6.9);
        $agora24 = $this->createAgora($user,'Entity Framework','');$action24 = $this->createAction($user,'Entity Framework',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> Entity Framework</b>?','yes',100,'no',0);$this->createUplink($agora24,$action24,5);$this->createUplink($job2,$agora24,6.7);
        $this->createUplink($job2,$agora4,6.6);
        $this->createUplink($job2,$agora7,5.2);
        $agora25 = $this->createAgora($user,'VB.Net','');$action25 = $this->createAction($user,'VB.Net',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> VB.Net</b>?','yes',100,'no',0);$this->createUplink($agora25,$action25,5);$this->createUplink($job2,$agora25,4.4);
        $agora26 = $this->createAgora($user,'Sharepoint','');$action26 = $this->createAction($user,'Sharepoint',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> Sharepoint</b>?','yes',100,'no',0);$this->createUplink($agora26,$action26,5);$this->createUplink($job2,$agora26,3.5);
        $this->createUplink($job2,$agora11,3.5);
        $agora27 = $this->createAgora($user,'VB','');$action27 = $this->createAction($user,'VB',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> VB</b>?','yes',100,'no',0);$this->createUplink($agora27,$action27,5);$this->createUplink($job2,$agora27,2.3);
        $this->createUplink($job2,$agora16,1.8);
        $agora28 = $this->createAgora($user,'Web API','');$action28 = $this->createAction($user,'Web API',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> Web API</b>?','yes',100,'no',0);$this->createUplink($agora28,$action28,5);$this->createUplink($job2,$agora28,1.4);
        $this->createUplink($job2,$agora13,1);
        $agora29 = $this->createAgora($user,'ADO','');$action29 = $this->createAction($user,'ADO',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> ADO</b>?','yes',100,'no',0);$this->createUplink($agora29,$action29,5);$this->createUplink($job2,$agora29,0.7);
        $agora30 = $this->createAgora($user,'Microsoft SQL Server','');$action30 = $this->createAction($user,'Microsoft SQL Server',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> Microsoft SQL Server</b>?','yes',100,'no',0);$this->createUplink($agora30,$action30,5);$this->createUplink($job2,$agora30,0.6);
        $this->createUplink($job2,$agora3,0.5);
        $agora31 = $this->createAgora($user,'Webforms','');$action31 = $this->createAction($user,'Webforms',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> Webforms</b>?','yes',100,'no',0);$this->createUplink($agora31,$action31,5);$this->createUplink($job2,$agora31,0.5);
        $this->createUplink($job2,$agora10,0.4);
        $agora32 = $this->createAgora($user,'Microsoft Dynamics','');$action32 = $this->createAction($user,'Microsoft Dynamics',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> Microsoft Dynamics</b>?','yes',100,'no',0);$this->createUplink($agora32,$action32,5);$this->createUplink($job2,$agora32,0.4);
        $agora33 = $this->createAgora($user,'BackBoneJS','');$action33 = $this->createAction($user,'BackBoneJS',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> BackBoneJS</b>?','yes',100,'no',0);$this->createUplink($agora33,$action33,5);$this->createUplink($job2,$agora33,0.3);
        $this->createUplink($job2,$agora18,0.1);
        $this->createUplink($job2,$agora12,0.1);
        $agora34 = $this->createAgora($user,'PHP','');$action34 = $this->createAction($user,'PHP',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> PHP</b>?','yes',100,'no',0);$this->createUplink($agora34,$action34,5);$this->createUplink($job3,$agora34,20.8);
        $this->createUplink($job3,$agora2,13.2);
        $this->createUplink($job3,$agora4,11.1);
        $this->createUplink($job3,$agora5,9.6);
        $this->createUplink($job3,$agora7,9.3);
        $this->createUplink($job3,$agora13,7);
        $agora35 = $this->createAgora($user,'Zend','');$action35 = $this->createAction($user,'Zend',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> Zend</b>?','yes',100,'no',0);$this->createUplink($agora35,$action35,5);$this->createUplink($job3,$agora35,6.3);
        $agora36 = $this->createAgora($user,'Symfony2','');$action36 = $this->createAction($user,'Symfony2',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> Symfony2</b>?','yes',100,'no',0);$this->createUplink($agora36,$action36,5);$this->createUplink($job3,$agora36,5.2);
        $this->createUplink($job3,$agora16,4.4);
        $this->createUplink($job3,$agora20,3.6);
        $agora37 = $this->createAgora($user,'Laravel','');$action37 = $this->createAction($user,'Laravel',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> Laravel</b>?','yes',100,'no',0);$this->createUplink($agora37,$action37,5);$this->createUplink($job3,$agora37,2.8);
        $agora38 = $this->createAgora($user,'Magento','');$action38 = $this->createAction($user,'Magento',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> Magento</b>?','yes',100,'no',0);$this->createUplink($agora38,$action38,5);$this->createUplink($job3,$agora38,1.9);
        $this->createUplink($job3,$agora10,1.3);
        $this->createUplink($job3,$agora14,1.1);
        $this->createUplink($job3,$agora11,0.8);
        $agora39 = $this->createAgora($user,'Wordpress','');$action39 = $this->createAction($user,'Wordpress',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> Wordpress</b>?','yes',100,'no',0);$this->createUplink($agora39,$action39,5);$this->createUplink($job3,$agora39,0.7);
        $agora40 = $this->createAgora($user,'CakePHP','');$action40 = $this->createAction($user,'CakePHP',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> CakePHP</b>?','yes',100,'no',0);$this->createUplink($agora40,$action40,5);$this->createUplink($job3,$agora40,0.4);
        $this->createUplink($job3,$agora28,0.4);
        $this->createUplink($job3,$agora8,0.1);
        $agora41 = $this->createAgora($user,'marketingacties','');$action41 = $this->createAction($user,'marketingacties',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> marketingacties</b>?','yes',100,'no',0);$this->createUplink($agora41,$action41,5);$this->createUplink($job4,$agora41,22.7);
        $agora42 = $this->createAgora($user,'marketingstrategie','');$action42 = $this->createAction($user,'marketingstrategie',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> marketingstrategie</b>?','yes',100,'no',0);$this->createUplink($agora42,$action42,5);$this->createUplink($job4,$agora42,15.6);
        $agora43 = $this->createAgora($user,'marktonderzoek','');$action43 = $this->createAction($user,'marktonderzoek',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> marktonderzoek</b>?','yes',100,'no',0);$this->createUplink($agora43,$action43,5);$this->createUplink($job4,$agora43,14.4);
        $agora44 = $this->createAgora($user,'marketingbudget','');$action44 = $this->createAction($user,'marketingbudget',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> marketingbudget</b>?','yes',100,'no',0);$this->createUplink($agora44,$action44,5);$this->createUplink($job4,$agora44,13.7);
        $agora45 = $this->createAgora($user,'marketingplan','');$action45 = $this->createAction($user,'marketingplan',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> marketingplan</b>?','yes',100,'no',0);$this->createUplink($agora45,$action45,5);$this->createUplink($job4,$agora45,12.5);
        $agora46 = $this->createAgora($user,'managing','');$action46 = $this->createAction($user,'managing',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> managing</b>?','yes',100,'no',0);$this->createUplink($agora46,$action46,5);$this->createUplink($job4,$agora46,9);
        $this->createUplink($job4,$agora39,4);
        $agora47 = $this->createAgora($user,'promotiecampagne','');$action47 = $this->createAction($user,'promotiecampagne',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> promotiecampagne</b>?','yes',100,'no',0);$this->createUplink($agora47,$action47,5);$this->createUplink($job4,$agora47,3.5);
        $agora48 = $this->createAgora($user,'Promotiemiddelen','');$action48 = $this->createAction($user,'Promotiemiddelen',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> Promotiemiddelen</b>?','yes',100,'no',0);$this->createUplink($agora48,$action48,5);$this->createUplink($job4,$agora48,1.7);
        $agora49 = $this->createAgora($user,'distributiekanalen','');$action49 = $this->createAction($user,'distributiekanalen',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> distributiekanalen</b>?','yes',100,'no',0);$this->createUplink($agora49,$action49,5);$this->createUplink($job4,$agora49,1.6);
        $agora50 = $this->createAgora($user,'search engine optimisation','');$action50 = $this->createAction($user,'search engine optimisation',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> search engine optimisation</b>?','yes',100,'no',0);$this->createUplink($agora50,$action50,5);$this->createUplink($job4,$agora50,1.3);
        $this->createUplink($job5,$agora39,22.8);
        $this->createUplink($job5,$agora45,15.7);
        $this->createUplink($job5,$agora5,12.3);
        $this->createUplink($job5,$agora20,11.6);
        $this->createUplink($job5,$agora42,8.7);
        $this->createUplink($job5,$agora41,8.5);
        $this->createUplink($job5,$agora48,6);
        $this->createUplink($job5,$agora47,6);
        $this->createUplink($job5,$agora44,5);
        $this->createUplink($job5,$agora43,3.4);
        $this->createUplink($job6,$agora45,23.6);
        $this->createUplink($job6,$agora27,22.1);
        $this->createUplink($job6,$agora50,19.3);
        $agora51 = $this->createAgora($user,'SAP','');$action51 = $this->createAction($user,'SAP',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b> SAP</b>?','yes',100,'no',0);$this->createUplink($agora51,$action51,5);$this->createUplink($job6,$agora51,14.9);
        $this->createUplink($job6,$agora41,8.8);
        $this->createUplink($job6,$agora44,5.3);
        $this->createUplink($job6,$agora39,4.4);
        $this->createUplink($job6,$agora46,1.5);





        return $this->redirect($this->generateUrl('homepage'));
    }

    public function newDbBnlearnKeywordsAsActionsAction() {
        $this->entityManager = $this->getDoctrine()->getManager();
        $this->emptyTheDatabase();

        /** @var $user User */
        $user = $this->get('security.context')->getToken()->getUser();

        $job1 = $this->createTechne($user,'Analist Programmeur','');
        $job2 = $this->createTechne($user,'Marketing Manager','');
        $agora1 = $this->createAgora($user,'Java developer','');$this->createUplink($job1,$agora1,50);
        $agora2 = $this->createAgora($user,'.net developer','');$this->createUplink($job1,$agora2,50);
        $agora3 = $this->createAgora($user,'php developer','');$this->createUplink($job1,$agora3,50);
        $agora4 = $this->createAgora($user,'manager','');$this->createUplink($job2,$agora4,50);
        $agora5 = $this->createAgora($user,'specialist','');$this->createUplink($job2,$agora5,50);
        $agora6 = $this->createAgora($user,'assistent','');$this->createUplink($job2,$agora6,50);
        $action1 = $this->createAction($user,'Java',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>Java</b>?','yes',100,'no',0);$this->createUplink($agora1,$action1,5);
        $action2 = $this->createAction($user,'Javascript',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>Javascript</b>?','yes',100,'no',0);$this->createUplink($agora1,$action2,5);
        $action3 = $this->createAction($user,'Oracle',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>Oracle</b>?','yes',100,'no',0);$this->createUplink($agora1,$action3,5);
        $action4 = $this->createAction($user,'CSS',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>CSS</b>?','yes',100,'no',0);$this->createUplink($agora1,$action4,5);
        $action5 = $this->createAction($user,'HTML',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>HTML</b>?','yes',100,'no',0);$this->createUplink($agora1,$action5,5);
        $action6 = $this->createAction($user,'Struts',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>Struts</b>?','yes',100,'no',0);$this->createUplink($agora1,$action6,5);
        $action7 = $this->createAction($user,'jQuery',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>jQuery</b>?','yes',100,'no',0);$this->createUplink($agora1,$action7,5);
        $action8 = $this->createAction($user,'MVC',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>MVC</b>?','yes',100,'no',0);$this->createUplink($agora1,$action8,5);
        $action9 = $this->createAction($user,'JSF',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>JSF</b>?','yes',100,'no',0);$this->createUplink($agora1,$action9,5);
        $action10 = $this->createAction($user,'Rest',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>Rest</b>?','yes',100,'no',0);$this->createUplink($agora1,$action10,5);
        $action11 = $this->createAction($user,'AngularJS',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>AngularJS</b>?','yes',100,'no',0);$this->createUplink($agora1,$action11,5);
        $action12 = $this->createAction($user,'Android',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>Android</b>?','yes',100,'no',0);$this->createUplink($agora1,$action12,5);
        $action13 = $this->createAction($user,'MySQL',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>MySQL</b>?','yes',100,'no',0);$this->createUplink($agora1,$action13,5);
        $action14 = $this->createAction($user,'PostgreSQL',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>PostgreSQL</b>?','yes',100,'no',0);$this->createUplink($agora1,$action14,5);
        $action15 = $this->createAction($user,'NodeJS',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>NodeJS</b>?','yes',100,'no',0);$this->createUplink($agora1,$action15,5);
        $action16 = $this->createAction($user,'Ajax',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>Ajax</b>?','yes',100,'no',0);$this->createUplink($agora1,$action16,5);
        $action17 = $this->createAction($user,'Spring MVC',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>Spring MVC</b>?','yes',100,'no',0);$this->createUplink($agora1,$action17,5);
        $action18 = $this->createAction($user,'iOS',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>iOS</b>?','yes',100,'no',0);$this->createUplink($agora1,$action18,5);
        $action19 = $this->createAction($user,'Perl',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>Perl</b>?','yes',100,'no',0);$this->createUplink($agora1,$action19,5);
        $action20 = $this->createAction($user,'Drupal',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>Drupal</b>?','yes',100,'no',0);$this->createUplink($agora1,$action20,5);
        $action21 = $this->createAction($user,'C#',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>C#</b>?','yes',100,'no',0);$this->createUplink($agora2,$action21,5);
        $this->createUplink($agora2,$action8,5);
        $this->createUplink($agora2,$action2,5);
        $action22 = $this->createAction($user,'WPF',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>WPF</b>?','yes',100,'no',0);$this->createUplink($agora2,$action22,5);
        $action23 = $this->createAction($user,'WCF',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>WCF</b>?','yes',100,'no',0);$this->createUplink($agora2,$action23,5);
        $this->createUplink($agora2,$action5,5);
        $action24 = $this->createAction($user,'Entity Framework',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>Entity Framework</b>?','yes',100,'no',0);$this->createUplink($agora2,$action24,5);
        $this->createUplink($agora2,$action4,5);
        $this->createUplink($agora2,$action7,5);
        $action25 = $this->createAction($user,'VB.Net',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>VB.Net</b>?','yes',100,'no',0);$this->createUplink($agora2,$action25,5);
        $action26 = $this->createAction($user,'Sharepoint',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>Sharepoint</b>?','yes',100,'no',0);$this->createUplink($agora2,$action26,5);
        $this->createUplink($agora2,$action11,5);
        $action27 = $this->createAction($user,'VB',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>VB</b>?','yes',100,'no',0);$this->createUplink($agora2,$action27,5);
        $this->createUplink($agora2,$action16,5);
        $action28 = $this->createAction($user,'Web API',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>Web API</b>?','yes',100,'no',0);$this->createUplink($agora2,$action28,5);
        $this->createUplink($agora2,$action13,5);
        $action29 = $this->createAction($user,'ADO',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>ADO</b>?','yes',100,'no',0);$this->createUplink($agora2,$action29,5);
        $action30 = $this->createAction($user,'Microsoft SQL Server',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>Microsoft SQL Server</b>?','yes',100,'no',0);$this->createUplink($agora2,$action30,5);
        $this->createUplink($agora2,$action3,5);
        $action31 = $this->createAction($user,'Webforms',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>Webforms</b>?','yes',100,'no',0);$this->createUplink($agora2,$action31,5);
        $this->createUplink($agora2,$action10,5);
        $action32 = $this->createAction($user,'Microsoft Dynamics',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>Microsoft Dynamics</b>?','yes',100,'no',0);$this->createUplink($agora2,$action32,5);
        $action33 = $this->createAction($user,'BackBoneJS',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>BackBoneJS</b>?','yes',100,'no',0);$this->createUplink($agora2,$action33,5);
        $this->createUplink($agora2,$action18,5);
        $this->createUplink($agora2,$action12,5);
        $action34 = $this->createAction($user,'PHP',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>PHP</b>?','yes',100,'no',0);$this->createUplink($agora3,$action34,5);
        $this->createUplink($agora3,$action2,5);
        $this->createUplink($agora3,$action4,5);
        $this->createUplink($agora3,$action5,5);
        $this->createUplink($agora3,$action7,5);
        $this->createUplink($agora3,$action13,5);
        $action35 = $this->createAction($user,'Zend',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>Zend</b>?','yes',100,'no',0);$this->createUplink($agora3,$action35,5);
        $action36 = $this->createAction($user,'Symfony2',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>Symfony2</b>?','yes',100,'no',0);$this->createUplink($agora3,$action36,5);
        $this->createUplink($agora3,$action16,5);
        $this->createUplink($agora3,$action20,5);
        $action37 = $this->createAction($user,'Laravel',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>Laravel</b>?','yes',100,'no',0);$this->createUplink($agora3,$action37,5);
        $action38 = $this->createAction($user,'Magento',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>Magento</b>?','yes',100,'no',0);$this->createUplink($agora3,$action38,5);
        $this->createUplink($agora3,$action10,5);
        $this->createUplink($agora3,$action14,5);
        $this->createUplink($agora3,$action11,5);
        $action39 = $this->createAction($user,'Wordpress',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>Wordpress</b>?','yes',100,'no',0);$this->createUplink($agora3,$action39,5);
        $action40 = $this->createAction($user,'CakePHP',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>CakePHP</b>?','yes',100,'no',0);$this->createUplink($agora3,$action40,5);
        $this->createUplink($agora3,$action28,5);
        $this->createUplink($agora3,$action8,5);
        $action41 = $this->createAction($user,'marketingacties',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>marketingacties</b>?','yes',100,'no',0);$this->createUplink($agora4,$action41,5);
        $action42 = $this->createAction($user,'marketingstrategie',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>marketingstrategie</b>?','yes',100,'no',0);$this->createUplink($agora4,$action42,5);
        $action43 = $this->createAction($user,'marktonderzoek',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>marktonderzoek</b>?','yes',100,'no',0);$this->createUplink($agora4,$action43,5);
        $action44 = $this->createAction($user,'marketingbudget',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>marketingbudget</b>?','yes',100,'no',0);$this->createUplink($agora4,$action44,5);
        $action45 = $this->createAction($user,'marketingplan',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>marketingplan</b>?','yes',100,'no',0);$this->createUplink($agora4,$action45,5);
        $action46 = $this->createAction($user,'managing',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>managing</b>?','yes',100,'no',0);$this->createUplink($agora4,$action46,5);
        $this->createUplink($agora4,$action39,5);
        $action47 = $this->createAction($user,'promotiecampagne',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>promotiecampagne</b>?','yes',100,'no',0);$this->createUplink($agora4,$action47,5);
        $action48 = $this->createAction($user,'Promotiemiddelen',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>Promotiemiddelen</b>?','yes',100,'no',0);$this->createUplink($agora4,$action48,5);
        $action49 = $this->createAction($user,'distributiekanalen',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>distributiekanalen</b>?','yes',100,'no',0);$this->createUplink($agora4,$action49,5);
        $action50 = $this->createAction($user,'search engine optimisation',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>search engine optimisation</b>?','yes',100,'no',0);$this->createUplink($agora4,$action50,5);
        $this->createUplink($agora5,$action39,5);
        $this->createUplink($agora5,$action45,5);
        $this->createUplink($agora5,$action5,5);
        $this->createUplink($agora5,$action20,5);
        $this->createUplink($agora5,$action42,5);
        $this->createUplink($agora5,$action41,5);
        $this->createUplink($agora5,$action48,5);
        $this->createUplink($agora5,$action47,5);
        $this->createUplink($agora5,$action44,5);
        $this->createUplink($agora5,$action43,5);
        $this->createUplink($agora6,$action45,5);
        $this->createUplink($agora6,$action27,5);
        $this->createUplink($agora6,$action50,5);
        $action51 = $this->createAction($user,'SAP',15,'declare your skill','http://www.google.be','Do you consider yourself as an expert in <b>SAP</b>?','yes',100,'no',0);$this->createUplink($agora6,$action51,5);
        $this->createUplink($agora6,$action41,5);
        $this->createUplink($agora6,$action44,5);
        $this->createUplink($agora6,$action39,5);
        $this->createUplink($agora6,$action46,5);



        return $this->redirect($this->generateUrl('homepage'));
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
            /** Outcome $entity*/
            foreach ($entity->getProbabilities() as $probability) {
                $this->entityManager->remove($probability);
            }
            $this->entityManager->remove($entity);
        }
        $entities = $this->entityManager->getRepository('LaCoreBundle:Progress')->findAll();
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
            /** LearningEntity $entity*/
            foreach ($entity->getUserProbabilities() as $probability) {
                $this->entityManager->remove($probability);
            }
            $this->entityManager->remove($entity);
        }
        $entities = $this->entityManager->getRepository('LaCoreBundle:Content')->findAll();
        foreach($entities as $entity) {
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
    private function createTechne($owner,$name,$contentString)
    {
        $agora = new Techne();
        $agora->setName($name);
        $agora->setOwner($owner);
        $content = new HtmlContent();
        $content->setContent($contentString);
        $agora->setContent($content);
        $this->entityManager->persist($content);
        $this->entityManager->persist($agora);
        $this->entityManager->flush();

        echo "created Techne $name<br>";
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

        $buttonOutcome1 = $this->createButtonOutcome('DISCARD',0);
        $buttonOutcome1->setLearningEntity($action);


        $buttonOutcome2 = $this->createButtonOutcome('LATER',40);
        $buttonOutcome2->setLearningEntity($action);

        $urlOutcome = $this->createUrlOutcome(60);
        $urlOutcome->setLearningEntity($action);

        $outcome1 = $this->createAnswerOutcome($answer1,$content,$affinity1,2);
        $outcome1->setLearningEntity($action);
        $outcome2 = $this->createAnswerOutcome($answer2,$content,$affinity2,2);
        $outcome2->setLearningEntity($action);
//        $outcome3 = $this->createAnswerOutcome("wrong",$content,0,2);
//        $outcome3->setLearningEntity($action);
//        $outcome4 = $this->createAnswerOutcome("wrong",$content,0,2);
//        $outcome4->setLearningEntity($action);

        $action->setContent($content);
        $action->setOwner($owner);

        $this->entityManager->persist($content);
        $this->entityManager->persist($action);

        echo "saved action ".$action->getId()."<br>";
        return $action;
    }

    private function createButtonOutcome($caption,$affinity) {
        $buttonOutcome = new ButtonOutcome();
        $buttonOutcome->setCaption($caption);
        $buttonOutcome->setAffinity($affinity);
        $this->entityManager->persist($buttonOutcome);
        return $buttonOutcome;
    }
    private function createUrlOutcome($affinity) {
        $urlOutcome = new UrlOutcome();
        $urlOutcome->setAffinity($affinity);
        $this->entityManager->persist($urlOutcome);
        return $urlOutcome;
    }
    private function createAnswerOutcome($answer,$content,$affinity) {
        $outcome = new AnswerOutcome();
        $a = new Answer();
        $a->setAnswer($answer);
        $a->setQuestion($content);
        $content->addAnswer($a);
        $outcome->setAnswer($a);
        $outcome->setAffinity($affinity);
        $outcome->setSelected(1);
        $this->entityManager->persist($a);
        $this->entityManager->persist($outcome);
        return $outcome;
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

}
