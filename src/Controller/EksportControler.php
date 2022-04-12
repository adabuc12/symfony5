<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\DbLog;
use App\Entity\Order;
use DateTime;

/**
 * Class EksportControler
 * @package App\Controller
 */
class EksportControler extends AbstractController {

    /**
     * @Route("/eksportwapro/{id}", name="eksport_wapro")
     */
    public function exportWapro(Order $order, DbLog $logger): Response {

        $date = new DateTime('NOW');
        $kontrahent = $order->getKontrahent();
        $voivodeship = 'brak';
        $name = $kontrahent->getName();
        $postcode = $kontrahent->getPostCode();
        $city = $kontrahent->getAdress();
        $adress = $kontrahent->getStreet();
        $email = $kontrahent->getEmail();
        $county = 'brak';
        $phone = $kontrahent->getPhone();
        $idGrupe = 1;
        $idFirm = 1;
        $productsCount = count($order->getItem());
        $customerEm =  $this->getDoctrine()->getManager('model_a');
        
        $export = '<ED>EKSPORT DANYCH Z PROGRAMU WAPRO Mag

                <HG>: Eksport danych z bazy danych: WAPRO
                Wersja bazy danych: 26.8704
                Dane eksportowane z modulu ZAMOWIENIA
                Eksport wykonano dnia:  4/08/2022'. $date->format('d/mm/yyyy') .' o godz. '. $date->format('H:i') .' </HG>

                <HM><ZAMOWIENIA>: EKSPORT ZAMOWIEN

                <HT><KONTRAHENT>: TABELA KONTRAHENT

                <HA> UWAGI, OSTRZEZENIE, WOJEWODZTWO, NAZWA_PELNA, NAZWA, SYM_KRAJU, NIP,
                REGON, FORMA_PLATNOSCI, KOD_KRESKOWY, KOD_POCZTOWY, MIEJSCOWOSC,
                ULICA_LOKAL, ADRES_WWW, WYROZNIK, POLE1, POLE2, POLE3, POLE4, POLE5,
                POLE6, POLE7, POLE8, POLE9, POLE10, NIPL, DOS_KOD, KTO_WPISAL,
                KTO_POPRAWIL, ADRES_EMAIL, KLUCZ, SYM_KRAJU_KOR, KOD_POCZTOWY_KOR,
                MIEJSCOWOSC_KOR, ULICA_LOKAL_KOR, WOJEWODZTWO_KOR, PESEL,
                DOKUMENT_TOZSAMOSCI_NAZWA, DOKUMENT_TOZSAMOSCI_NUMER,
                DOKUMENT_WYDANY_PRZEZ, RAKS_KOD_KONTRAHENTA, ZAKUP_WG_CEN, NR_AKCYZOWY,
                DOM_SYM_WAL, POWIAT, POWIAT_KOR, ID_ALLEGRO, LOGIN_ALLEGRO,
                TELEFON_FIRMOWY, RODZAJ_TRANSAKCJI_HANDLOWEJ, NR_EORI, ID_RACHUNKU_FIRMY,
                ID_FORMY_DOSTAWY_DOM, ID_MIEJSCA_DOSTAWY_DOM, ID_ETYKIETY,
                ID_KONTRAHENTA_JST, KP_ID_Zdarzenia, ID_HANDLOWCA, DOMYSLNY_RABAT,
                ID_PLATNIKA, ID_KONTRAHENTA, ID_GRUPY, ID_FIRMY, ID_TABELI_ODSETEK,
                ID_RACHUNKU, ID_KLASYFIKACJI, LIMIT_KUPIECKI, LOJAL_WARTOSC,
                PRG_LOJAL_KWOTA, RODO_DATA, DNI_PO_TERMINIE, LOJAL_PKT,
                DOKUMENT_DATA_WYDANIA, TERMIN_NALEZNOSCI, TERMIN_ZOBOWIAZAN,
                KOD_KONTRAHENTA, PRIORYTET, PLATNIK_VAT, ODBIORCA, DOSTAWCA,
                DRUKUJ_OSTRZEZENIE, POKAZUJ_OSTRZEZENIE, KontrahentUE,
                ZGODA_NA_PRZETWARZANIE, UTWORZONY_W_SYSTEMIE_ZEWNETRZNYM, MP,
                PODLEGA_PROMOCJI, WEWNETRZNY, OPERATOR_PLATNOSCI,
                ZGODA_NA_FAKTURY_ELEKTRONICZNE, BLOKOWANIE, Incydentalny, ZABLOKOWANY,
                AUTOFISKALIZACJA, ZBIORCZE_PLATNOSCI, RODO_ZANONIMIZOWANY,
                PLATNIK_CUKROWY, KTORY_MAIL_DO_RAPORTOW, DOSTEPNY_W_B2B, PP_PREFEROWANA,
                PRG_LOJAL, VAT_CZYNNY, AKCYZA_ZWOLNIONY, GUID_RODO_KONTRAHENT </HA>
                <LT>11111111111111111111111111111111111111111111111111100000000000000000000000000000000000000000000000000001</LT>
                <LR>2</LR>
                <SL>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>'.$voivodeship.'</DT>
                <DT>'.$name.'</DT>
                <DT>'.$name.'</DT>
                <DT>PL</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>gotówka</DT>
                <DT>brak</DT>
                <DT>'.$postcode.'</DT>
                <DT>'.$city.'</DT>
                <DT>'.$adress.'</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>Ewa Oliwa '.$date->format('dd.mm.YYYY H:i:s').'</DT>
                <DT>Ewa Oliwa '.$date->format('dd.mm.YYYY H:i:s').'</DT>
                <DT>'.$email.'</DT>
                <DT>'.$name.'  '.$postcode.' '.$city.' '.$adress.'</DT>
                <DT>PL</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>Dowód osobisty</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>Typ</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>'.$county.'</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>'.$phone.'</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>6233</DT>
                <DT>6233</DT>
                <DT>'.$idGrupe.'</DT>
                <DT>'.$idFirm.'</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>1</DT>
                <DT>0.0000</DT>
                <DT>0.0000</DT>
                <DT>0.00</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>brak</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>5930</DT>
                <DT>2</DT>
                <DT>1</DT>
                <DT>1</DT>
                <DT>0</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>0</DT>
                <DT>brak</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>1</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>D89C693F-5E10-492C-A406-BFA56A348EBE</DT>
                </EL>
                <SL>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>'.$voivodeship.'</DT>
                <DT>'.$name.'</DT>
                <DT>'.$name.'</DT>
                <DT>PL</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>gotówka</DT>
                <DT>brak</DT>
                <DT>'.$postcode.'</DT>
                <DT>'.$city.'</DT>
                <DT>'.$adress.'</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>Ewa Oliwa '.$date->format('dd.mm.YYYY H:i:s').'</DT>
                <DT>Ewa Oliwa '.$date->format('dd.mm.YYYY H:i:s').'</DT>
                <DT>'.$email.'</DT>
                <DT>'.$name.'  '.$postcode.' '.$city.' '.$adress.'</DT>
                <DT>PL</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>Dowód osobisty</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>Typ</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>'.$county.'</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>'.$phone.'</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>6233</DT>
                <DT>6233</DT>
                <DT>'.$idGrupe.'</DT>
                <DT>'.$idFirm.'</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>1</DT>
                <DT>0.0000</DT>
                <DT>0.0000</DT>
                <DT>0.00</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>brak</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>5930</DT>
                <DT>2</DT>
                <DT>1</DT>
                <DT>1</DT>
                <DT>0</DT>
                <DT>brak</DT>
                <DT>brak</DT>
                <DT>0</DT>
                <DT>brak</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>1</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>0</DT>
                <DT>D89C693F-5E10-492C-A406-BFA56A348EBE</DT>
                </EL>
                </HT>

        <HT><KONTAKT>: TABELA KONTAKT

        <HA> UWAGI, NAZWISKO, IMIE, TYTUL, STANOWISKO, TEL, TEL_KOM, FAX, E_MAIL,
        E_MAIL_DW, GG_NR, SKYPE_NR, ID_KONTAKTU, ID_KONTRAHENTA, RODO_DATA,
        RODO_ZANONIMIZOWANY, WYSYLAJ_RAPORTY, RAPORTY_MAG, RAPORTY_ZAM,
        RAPORTY_OFE, RAPORTY_HAN, RAPORTY_FIN, DOMYSLNY, SMS_ZGODA,
        GUID_RODO_KONTAKT </HA>
        <LT>1111111111110000000000001</LT>
        <LR>0</LR>
        </HT>

        <HT><KLASYFIKACJA_KONTRAHENTA>: TABELA KLASYFIKACJA_KONTRAHENTA

        <HA> OPIS, NAZWA, KONTO_FK, ID_KLASYFIKACJI, ID_FIRMY,
        ANALITYKA_KONTRAHENTA, DOMYSLNA </HA>
        <LT>1110000</LT>
        <LR>1</LR>
        <SL>
        <DT>brak</DT>
        <DT>Ogólna</DT>
        <DT>brak</DT>
        <DT>1</DT>
        <DT>1</DT>
        <DT>brak</DT>
        <DT>1</DT>
        </EL>
        </HT>

        <HT><BANKI>: TABELA BANKI

        <HA> SYMBOL, NAZWA, NUMER_BANKU, MIEJSCOWOSC, ULICA_LOKAL, KOD_POCZTOWY,
        TEL, E_MAIL, ADRES_WWW, SWIFT, ID_BANKU </HA>
        <LT>11111111110</LT>
        <LR>0</LR>
        </HT>

        <HT><RACHUNEK_KONTRAHENTA>: TABELA RACHUNEK_KONTRAHENTA

        <HA> NUMER_RACHUNKU, SYM_WALUTY, LK, NAZWA, NUMER_RB, ID_BANKU,
        ID_RACHUNKU, ID_KONTRAHENTA, RODO_DATA, RODO_ZANONIMIZOWANY, PP_BLOKADA,
        NRB, AKTYWNY, GUID_RODO_RACHUNEK_KONTRAHENTA </HA>
        <LT>11111000000001</LT>
        <LR>0</LR>
        </HT>

        <HT><CENA>: TABELA CENA

        <HA> OPIS, NAZWA, ID_CENY, ID_FIRMY, ID_CENY_ZAL, KOLEJNOSC,
        PROCENT_CENY_ZAL, KWOTA_CENY_ZAL, ZAOKR_CENY_ZAL, AKT_CEN_PRZY_DOSTAWIE,
        RODZAJ_ZAL, DLA_WSZYSTKICH </HA>
        <LT>110000000000</LT>
        <LR>1</LR>
        <SL>
        <DT>brak</DT>
        <DT>Detaliczna</DT>
        <DT>1</DT>
        <DT>1</DT>
        <DT>0</DT>
        <DT>2</DT>
        <DT>.0000</DT>
        <DT>.0000</DT>
        <DT>2</DT>
        <DT>1</DT>
        <DT>0</DT>
        <DT>0</DT>
        </EL>
        </HT>

        <HT><GRUPA_KONTRAHENTA>: TABELA GRUPA_KONTRAHENTA

        <HA> OPIS, NAZWA, ID_GRUPY, ID_FIRMY, ID_CENY, NARZUT, DOMYSLNA </HA>
        <LT>1100000</LT>
        <LR>1</LR>
        <SL>
        <DT>brak</DT>
        <DT>Ogólna</DT>
        <DT>1</DT>
        <DT>1</DT>
        <DT>brak</DT>
        <DT>.0000</DT>
        <DT>1</DT>
        </EL>
        </HT>

        <HT><KATEGORIA_ARTYKULU>: TABELA KATEGORIA_ARTYKULU

        <HA> KOD_VAT, KOD_VAT_ZAK, KONTO_FK_KATEGORII, DOMYSLNY_KOD_JPK, NAZWA,
        MASKA_I_KATALOG, MASKA_I_HANDL, POLE1, POLE2, POLE3, POLE4, POLE5, POLE6,
        POLE7, POLE8, POLE9, POLE10, ID_DOMYSLNEJ_CENY, ID_KATEGORII,
        ID_MAGAZYNU, NUMER_DZIALU, DOMYSLNA, GUID_KATEGORIA </HA>
        <LT>11111111111111111000001</LT>
        <LR>'.$productsCount.'</LR>
        <SL>
        <DT>23</DT>
        <DT>23</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>OGÓLNA</DT>
        <DT>OGO######</DT>
        <DT>OGO######</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>0</DT>
        <DT>8</DT>
        <DT>1</DT>
        <DT>brak</DT>
        <DT>0</DT>
        <DT>47CC92F7-5A4E-477A-92E4-468479051B9B</DT>
        </EL>
        <SL>
        <DT>23</DT>
        <DT>23</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>OGÓLNA</DT>
        <DT>OGO######</DT>
        <DT>OGO######</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>1</DT>
        <DT>11</DT>
        <DT>1</DT>
        <DT>brak</DT>
        <DT>0</DT>
        <DT>2F10F458-8CC7-4DA5-911C-DEE276061EDC</DT>
        </EL>
        </HT>

        <HT><JEDNOSTKA>: TABELA JEDNOSTKA

        <HA> NAZWA, SKROT, ID_JEDNOSTKI, ID_ARTYKULU, ID_FIRMY, PRZELICZNIK,
        PODZIELNA, WZORZEC_INNEJ, ARCHIWALNA </HA>
        <LT>110000000</LT>
        <LR>2</LR>
        <SL>
        <DT>m2</DT>
        <DT>m2</DT>
        <DT>5</DT>
        <DT>brak</DT>
        <DT>1</DT>
        <DT>1.000000</DT>
        <DT>1</DT>
        <DT>0</DT>
        <DT>0</DT>
        </EL>
        <SL>
        <DT>Sztuka</DT>
        <DT>szt.</DT>
        <DT>1</DT>
        <DT>brak</DT>
        <DT>1</DT>
        <DT>1.000000</DT>
        <DT>1</DT>
        <DT>0</DT>
        <DT>0</DT>
        </EL>
        </HT>

        <HT><ARTYKUL>: TABELA ARTYKUL

        <HA> OSTRZEZENIE, OPIS, UWAGI, VAT_ZAKUPU, VAT_SPRZEDAZY,
        WYLACZ_CENY_IND, KRAJ_POCHODZENIA, PRODUCENT, KOD_CN, NAZWA_INTRASTAT,
        JED_WAGI, JED_WYMIARU, WYROZNIK, SYM_WAL, POLE1, POLE2, POLE3, POLE4,
        POLE5, POLE6, POLE7, POLE8, POLE9, POLE10, NAZWA, NAZWA_ORYG, NAZWA2,
        INDEKS_KATALOGOWY, IND_KAT_TOWAR_ZAST, INDEKS_HANDLOWY, RODZAJ, SWWKU,
        PKWIU, KOD_KRESKOWY, NAZWA_CERTYFIKATU, LOKALIZACJA, JEDNOSTKA_PROD,
        ZASADA_ZDEJMOWANIA_ZE_STANU, AKCYZA_JM, NAZWA_CALA, INDEKS_PRODUCENTA,
        RODZAJ_TRANSAKCJI_HANDLOWEJ, ID_KATEGORII_TREE, ID_JEDNOSTKI_INTRASTAT,
        ID_ETYKIETY, ID_PRODUCENTA, ID_DOSTAWCY_PREFEROWANEGO, ID_FPROM,
        ID_ARTYKULU_PROD, ID_ARTYKULU, ID_MAGAZYNU, ID_KATEGORII, ID_CENY_DOM,
        ID_JEDNOSTKI, ID_JEDNOSTKI_ZAK, ID_JEDNOSTKI_SPRZ, ID_JEDNOSTKI_REF,
        ID_OPAKOWANIA_REF, CENA_N_KGO, CENA_B_KGO, WAGA, CENA_PROMOCJI_N,
        CENA_PROMOCJI_B, PROMOCJA_PROCENT, C_ZAKUPU_NETTO_WAL,
        C_ZAKUPU_BRUTTO_WAL, WYMIAR_W, WYMIAR_S, WYMIAR_G, STAN, ILOSC_EDYTOWANA,
        ZAMOWIONO, DO_REZERWACJI, ZAREZERWOWANO, OD_DOSTAWCOW, DO_ODBIORCOW,
        PRZELICZNIK_PROD, ILOSC_PROD, CENA_ZAKUPU_BRUTTO, CENA_ZAKUPU_NETTO,
        STAN_MINIMALNY, STAN_MAKSYMALNY, MIN_NARZUT, AKCYZA_PRZELICZNIK,
        AKCYZA_STAWKA_ZA_JM, PC_ILOSC_ML, PC_ILOSC_GR_NA100ML, PC_KWOTA_STALA,
        PC_KWOTA_ZMIENNA, PODATEK_CUKROWY, PRG_ILOSC, AKCYZA_PRZELICZNIK_JM_JA,
        DATA_ZM_CZ, DATA_CERTYFIKATU, PLU, PROMOCJA_OD, PROMOCJA_DO,
        PROMOCJA_RABAT, ZABLOKOWANY, AKT_CEN_PRZY_DOSTAWIE, TYP_OPLATY,
        JEST_ZDJECIE, AKTYWNY_DLA_SYS_ZEW, POKAZUJ_OSTRZEZENIE, CERTYFIKAT,
        PC_AKTYWNY, PRG_LOJAL, PODLEGA_PP, PC_KOF_TAUR, PC_SOKI, PC_ROZTWOR_WE,
        PODLEGA_RABATOWANIU, ODWROTNY, DOSTEPNY_W_AUKCJACH, AKCYZA,
        WYMUSZAJ_DOSTAWY, ROZCHODY_ROZBIJAJ_PO_DOSTAWACH, MARZOWY,
        DOMYSLNY_VAT_ZA_GRANICA, DOSTEPNY_W_SKLEPIE_INTER, GUID_ARTYKUL,
        GUID_ARTYKUL, GUID_ARTYKUL </HA>
        <LT>111111111111111111111111111111111111111111000000000000000000000000000000000000000000000000000000000000000000000000000000111</LT>
        <LR>2</LR>
        <SL>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>NA PALECIE: 11,5 m2</DT>
        <DT>23</DT>
        <DT>23</DT>
        <DT>0</DT>
        <DT>PL</DT>
        <DT>KOST-BET</DT>
        <DT>brak</DT>
        <DT>ROSSANO 6cm Grafit</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>ROSSANO 6  gładki TOFFI</DT>
        <DT>ROSSANO 6cm gładkie A4 Czekolada</DT>
        <DT>brak</DT>
        <DT>KOS000915</DT>
        <DT>brak</DT>
        <DT>KOS000915</DT>
        <DT>Towar</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>DOMYSLNIE</DT>
        <DT>brak</DT>
        <DT>ROSSANO 6  gładki TOFFI</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>1</DT>
        <DT>brak</DT>
        <DT>0</DT>
        <DT>2230</DT>
        <DT>brak</DT>
        <DT>0</DT>
        <DT>1462</DT>
        <DT>1462</DT>
        <DT>1</DT>
        <DT>8</DT>
        <DT>1</DT>
        <DT>5</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0.0000</DT>
        <DT>0.0000</DT>
        <DT>1560.000</DT>
        <DT>0.0000</DT>
        <DT>0.0000</DT>
        <DT>0.0000</DT>
        <DT>0.0000</DT>
        <DT>0.0000</DT>
        <DT>0.000</DT>
        <DT>0.000</DT>
        <DT>0.000</DT>
        <DT>0.000000</DT>
        <DT>0.000000</DT>
        <DT>126.500000</DT>
        <DT>0.000000</DT>
        <DT>0.000000</DT>
        <DT>0.000000</DT>
        <DT>126.500000</DT>
        <DT>1.000000</DT>
        <DT>1.000000</DT>
        <DT>48.7600</DT>
        <DT>39.6400</DT>
        <DT>0.000000</DT>
        <DT>0.000000</DT>
        <DT>0.00</DT>
        <DT>1.000000</DT>
        <DT>0.0000</DT>
        <DT>0.000000</DT>
        <DT>0.000000</DT>
        <DT>0.0000</DT>
        <DT>0.0000</DT>
        <DT>0.000000</DT>
        <DT>0.00</DT>
        <DT>1.000000</DT>
        <DT>80799</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>1</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>1</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>F31CA399-537A-41C7-8F1D-60E704F67DDC</DT>
        </EL>
        <SL>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>23</DT>
        <DT>23</DT>
        <DT>0</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>Paleta KOSTBET</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>Paleta Kost-Bet</DT>
        <DT>Paleta Kost-Bet</DT>
        <DT>brak</DT>
        <DT>Pal000058</DT>
        <DT>brak</DT>
        <DT>Pal000058</DT>
        <DT>Towar</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>DOMYSLNIE</DT>
        <DT>brak</DT>
        <DT>Paleta Kost-Bet</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>1</DT>
        <DT>brak</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>brak</DT>
        <DT>64</DT>
        <DT>64</DT>
        <DT>1</DT>
        <DT>11</DT>
        <DT>1</DT>
        <DT>1</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>0.0000</DT>
        <DT>0.0000</DT>
        <DT>0.000</DT>
        <DT>0.0000</DT>
        <DT>0.0000</DT>
        <DT>0.0000</DT>
        <DT>0.0000</DT>
        <DT>0.0000</DT>
        <DT>0.000</DT>
        <DT>0.000</DT>
        <DT>0.000</DT>
        <DT>1117.000000</DT>
        <DT>0.000000</DT>
        <DT>850.000000</DT>
        <DT>0.000000</DT>
        <DT>0.000000</DT>
        <DT>20.000000</DT>
        <DT>830.000000</DT>
        <DT>1.000000</DT>
        <DT>1.000000</DT>
        <DT>61.5000</DT>
        <DT>50.0000</DT>
        <DT>0.000000</DT>
        <DT>0.000000</DT>
        <DT>0.00</DT>
        <DT>1.000000</DT>
        <DT>0.0000</DT>
        <DT>0.000000</DT>
        <DT>0.000000</DT>
        <DT>0.0000</DT>
        <DT>0.0000</DT>
        <DT>0.000000</DT>
        <DT>0.00</DT>
        <DT>1.000000</DT>
        <DT>80818</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>1</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>1</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>1</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>9CE3019B-567B-4098-B399-5CF6AD0663B7</DT>
        </EL>
        </HT>

        <HT><ZAMIENNIK>: TABELA ZAMIENNIK

        <HA> ID_ARTYKULU, ID_ZAMIENNIKA, ID_ZAMIENNIKA_ARTYKULU, ILOSC,
        KOLEJNOSC, BLOKUJ_PRODUKCJE </HA>
        <LT>000000</LT>
        <LR>0</LR>
        </HT>

        <HT><DEFINICJA_PRODUKTU>: TABELA DEFINICJA_PRODUKTU

        <HA> OPIS, JEDNOSTKA, ID_DEFINICJI, ID_ARTYKULU, ID_WARIANTU,
        ID_SKLADNIKA, ILOSC, PRZELICZNIK, KOLEJNOSC </HA>
        <LT>110000000</LT>
        <LR>0</LR>
        </HT>

        <HT><ZAMOWIENIE>: TABELA ZAMOWIENIE

        <HA> UWAGI, OBLICZANIE_WG_CEN, STAN_REALIZ, STATUS_ZAM, POLE1, POLE2,
        POLE3, POLE4, POLE5, POLE6, POLE7, POLE8, POLE9, POLE10, NUMER,
        NR_ZAMOWIENIA_KLIENTA, SYM_WAL, INFORMACJE_DODATKOWE, KOD_KRESKOWY,
        KONTRAHENT_NAZWA, OSOBA_ZAMAWIAJACA, FORMA_PLATNOSCI, NUMER_PRZESYLKI,
        ZAMOWIENIE_INTERNETOWE_ID, ID_OPERATORA_PRZESYLKI, ID_KONTAKTU,
        AUTONUMER, ID_ZAMOWIENIA, ID_KONTRAHENTA, ID_FIRMY, ID_MAGAZYNU,
        ID_PRACOWNIKA, ID_UZYTKOWNIKA, ID_RACHUNKU, ID_ETYKIETY, ZALICZKA,
        WARTOSC_BRUTTO, WARTOSC_NETTO, WARTOSC_NETTO_WAL, WARTOSC_BRUTTO_WAL,
        PRZELICZNIK_WAL, RABAT_NARZUT, DATA_UTWORZENIA_WIERSZA,
        DATA_BLOKADY_WIERSZA, DNI_PLATNOSCI, DATA, DATA_REALIZACJI,
        DATA_KURS_WAL, DOK_ZABLOKOWANY, TRYBREJESTRACJI, PRIORYTET,
        AUTO_REZERWACJA, DOK_WAL, TYP, FAKTURA_DO_ZAMOWIENIA,
        ZAMOWIENIE_INTERNETOWE, DOKUMENT_Z_BUFORA, DOK_ZWERYFIKOWANY,
        OPLACONE_INTERNETOWO, TYP_SYS_INTERNETOWEGO </HA>
        <LT>111111111111111111111111000000000000000000110000000000000000</LT>
        <LR>1</LR>
        <SL>
        <DT>Odbiór Osobisty, 608-282-533, ZALICZKA 1000 ZŁ GOTÓWKĄ!! 
          Oświadczenia: 
        - Zamawiający zapewnia wszelkie zgody i pozwolenia na wjazd auta cięzarowego, bezpieczny rozładunek towaru. 
        - Zamawiający ma obowiązek poinformowania o możliwych problemach z wjazdem auta cięzarowego, w przypadku braku informacji na zamawiającym spoczywają dodatkowe koszty zwiazane z przeładunkiem lub zmianą transportu. 
        - Zwrotu towaru tylko pełnopaletowego, nie rozpakowanego, w terminie nie przekraczającym 14 dni od daty dostarczenia. 
        - Podpisanie zamówienia i wpłata zaliczki skutkuje nabyciem towaru. 
        - Towar dostpny na zamówienie nie podlega zwrotowi. 
        - Zamówienie towaru nie odzwierciedla istniejących stanów magazynowych, w przypadku braku dostepności towaru u producenta, zamawiąjący wyraża zgodę na wydłużony okres oczekiwania na towar. 
        - Palety mozna zwrócić do 180 dni od daty dostarczenia.
        </DT>
        <DT>Brutto</DT>
        <DT>N</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>nie dotyczy</DT>
        <DT>brak</DT>
        <DT>nie dotyczy</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>ZO 0285/22</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>Janusz Juszczak</DT>
        <DT>brak</DT>
        <DT>gotówka</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>0</DT>
        <DT>285</DT>
        <DT>3682751</DT>
        <DT>6233</DT>
        <DT>1</DT>
        <DT>1</DT>
        <DT>2</DT>
        <DT>3000002</DT>
        <DT>1</DT>
        <DT>0</DT>
        <DT>.0000</DT>
        <DT>2820.3000</DT>
        <DT>2292.9300</DT>
        <DT>.0000</DT>
        <DT>.0000</DT>
        <DT>1.00000000</DT>
        <DT>.00</DT>
        <DT>2022-04-08 12:08:24.783</DT>
        <DT>2022-04-08 12:16:23.167</DT>
        <DT>0</DT>
        <DT>80820</DT>
        <DT>80820</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>2</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>1</DT>
        <DT>2</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        </EL>
        </HT>

        <HT><POZYCJA_ZAMOWIENIA>: TABELA POZYCJA_ZAMOWIENIA

        <HA> OPIS, ZNACZNIK_CENY, NR_SERII, POLE1, POLE2, POLE3, POLE4, POLE5,
        POLE6, POLE7, POLE8, POLE9, POLE10, KOD_VAT, JEDNOSTKA,
        ID_POZYCJI_ZAMOWIENIA, ID_ZAMOWIENIA, ID_ARTYKULU, ID_WARIANTU,
        ID_ETYKIETY, ID_DOSTAWY_REZ, ID_POZYCJI_OFERTY, NARZUT, DO_REZ_USER,
        DO_REZ_POP, STAN_ZREALIZOWANO, ZAMOWIONO, ZREALIZOWANO, DO_REALIZACJI,
        ZAREZERWOWANO, DO_REZERWACJI, CENA_NETTO, CENA_BRUTTO, CENA_NETTO_WAL,
        CENA_BRUTTO_WAL, PRZELICZNIK, DATA_WAZNOSCI, TRYBREJESTRACJI, TYP,
        GUID_POZYCJA_ZAMOWIENIA </HA>
        <LT>1111111111111110000000000000000000000001</LT>
        <LR>2</LR>
        <SL>
        <DT>brak</DT>
        <DT>k</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>23</DT>
        <DT>szt.</DT>
        <DT>3617459</DT>
        <DT>3682751</DT>
        <DT>64</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>brak</DT>
        <DT>.0000</DT>
        <DT>.000000</DT>
        <DT>.000000</DT>
        <DT>.000000</DT>
        <DT>3.000000</DT>
        <DT>.000000</DT>
        <DT>.000000</DT>
        <DT>.000000</DT>
        <DT>.000000</DT>
        <DT>50.0000</DT>
        <DT>61.5000</DT>
        <DT>.0000</DT>
        <DT>.0000</DT>
        <DT>1.000000</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>1</DT>
        <DT>14634ED3-23B7-EC11-8D55-0050C25F3991</DT>
        </EL>
        <SL>
        <DT>brak</DT>
        <DT>m</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>brak</DT>
        <DT>23</DT>
        <DT>m2</DT>
        <DT>3617458</DT>
        <DT>3682751</DT>
        <DT>1462</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>brak</DT>
        <DT>.0000</DT>
        <DT>.000000</DT>
        <DT>.000000</DT>
        <DT>.000000</DT>
        <DT>34.500000</DT>
        <DT>.000000</DT>
        <DT>.000000</DT>
        <DT>.000000</DT>
        <DT>.000000</DT>
        <DT>62.1100</DT>
        <DT>76.4000</DT>
        <DT>.0000</DT>
        <DT>.0000</DT>
        <DT>1.000000</DT>
        <DT>0</DT>
        <DT>0</DT>
        <DT>1</DT>
        <DT>13634ED3-23B7-EC11-8D55-0050C25F3991</DT>
        </EL>
        </HT>

        <HT><KOD_EGZEMPLARZA>: TABELA KOD_EGZEMPLARZA

        <HA> KOD_EGZEMPLARZA, KOD_EGZEMPLARZA_KOPIA, ID_POZYCJI_ZAMOWIENIA,
        ID_POZYCJI_DOK_MAG, ID_POZYCJI_SADU, ID_POZYCJI_ZLECENIA,
        ID_POZYCJI_OFERTY, ID_KODU </HA>
        <LT>11000000</LT>
        <LR>0</LR>
        </HT>

        <HT><OSOBA_POWIAZANA>: TABELA OSOBA_POWIAZANA

        <HA> FUNKCJA, NAZWA, ID_FIRMY, ID_OSOPOW, ID_KONTRAHENTA, RODO_DATA,
        RODO_ZANONIMIZOWANY, DOKUMENT, CZY_DOMYSLNY, GUID_RODO_OSOBA_POWIAZANA </HA>
        <LT>1100000001</LT>
        <LR>0</LR>
        </HT>

        </HM>

        </ED>KONIEC EKSPORTU DANYCH Z PROGRAMU WAPRO Mag

        ';
        
        return $this->redirectToRoute('cart');
    }

}
