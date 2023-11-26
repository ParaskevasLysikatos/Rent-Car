@php
$printings = isset($printings) ? $printings : [];
// dd($printings);
@endphp


<style>
    .wrapper {
        position: relative;
        width: 400px;
        height: 200px;
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .signature-pad {
        position: absolute;
        left: 0;
        top: 0;
        width: 400px;
        height: 200px;
        background-color: white;

    }

    fieldset.hide {
        display: none;
    }

    fieldset.show {
        display: block;
    }

    select:focus,
    input:focus {
        -moz-box-shadow: none !important;
        -webkit-box-shadow: none !important;
        box-shadow: none !important;
        border: 1px solid #2196F3 !important;
        outline-width: 0 !important;
        font-weight: 400;
    }

    .tabs {
        margin: 2px 5px 0px 5px;
        padding-bottom: 10px;
        cursor: pointer;
    }

    .tabs:hover,
    .tabs.active {
        border-bottom: 1px solid #2196F3;
    }

    a:hover {
        text-decoration: none;
        color: #1565C0;
    }

    .box {
        margin-bottom: 10px;
        border-radius: 5px;
        padding: 10px;
    }

    .modal-backdrop {
        background-color: #64B5F6;
    }

    .line {
        background-color: #CFD8DC;
        height: 1px;
        width: 100%;
    }

    @media screen and (max-width: 768px) {
        .tabs h6 {
            font-size: 12px;
        }
    }

    div.scroll {
        background-color: white;
        height: 300px;
        overflow: auto;
        text-align: justify;
        padding: 20px;
    }
</style>


<!-- The Modal -->
<div class="modal fade" id="printModalFiles">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{ __('Εκτύπωση') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div id="printings" class="h-100">
                    @foreach ($printings as $printing)
                        @if ($printing)
                            <div class="">
                                <label for="printing-{{ $printing->name }}">{{ $printing->name }}</label>
                                <input id="printing-{{ $printing->name }}" type="checkbox"
                                    value="{{ $printing->path }}" />
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button id="print-selected-files" type="button" class="btn btn-primary">Εκτύπωση</button>
            </div>

        </div>
    </div>
</div>

<!-- The Modal 2 signature -->
<div class="modal fade" id="printModalFiles2">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{ __('Εκτύπωση Υπογραφή') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class='row ml-2'>
                <div class="tabs active" id="tab01">
                    <h6 class="text-muted">Υπογραφή excess</h6>
                </div>
                <div class="tabs" id="tab02">
                    <h6 class="font-weight-bold">Υπογραφή οδηγού</h6>
                </div>
                <div class="tabs" id="tab03">
                    <h6 class="font-weight-bold">Υπογραφή δεύτερου οδηγού</h6>
                </div>
                {{-- line after tabs --}}
                <div class="line"></div>
            </div>


            {{-- first tab --}}
            <fieldset id="tab011" class="hide show">
                <div class="bg-light">
                    <h5 class="text-center mb-4 mt-0 pt-4">Υπογραφή excess</h5>
                </div>
                <div class="px-3">
                    <!-- Modal body 1 -->
                    <div class="modal-body">
                        <div class="wrapper">
                            <canvas id="signature-pad1" class="signature-pad" width="400" height="200"></canvas>
                        </div>
                        <button class='mr-4 btn btn-primary' id="save-png1">Αποθήκευση</button>
                        <button class='mr-2 btn btn-danger' id="delete-png1">Διαγραφή</button>
                        {{-- <button id="save-jpeg">Save as JPEG</button>
                <button id="save-svg">Save as SVG</button> --}}
                        {{-- <button id="draw">Draw</button> --}}
                        {{-- <button id="erase">Erase</button> --}}
                        <button class='mr-2 btn btn-success' id="clear1">Clear</button>
                        @if (isset($rental))
                            @if (file_exists(public_path('../signatures/signatureExcess-' . $rental->sequence_number . '.png')))
                                <button class='mr-2 btn btn-secondary' id="see1">Υπογραφή</button>
                            @else
                                <p id="see1"> </p>
                            @endif
                        @endif

                        @if (isset($rental))
                            <input hidden id='myRental1' type='text' value="{{ $rental->id }}">
                            <input hidden id='myRentalSeq1' type='text' value="{{ $rental->sequence_number }}">
                        @endif
                        <br>
                        <br>
                        <p class="alert-success" style='display: none;' id="signatureConfirm1">Saved excess
                            successfully</p>
                        <p class="alert-warn" style='display: none;' id="signatureDelete1">Deleted excess
                            successfully</p>
                        <img width="400" height="300" style='display: none;' id='see1Image'>
                    </div>
                </div>
                <div class="scroll">
                    I declare that I have received a copy of this document and I have read and accepted the Terms &
                    Conditions as well as all additional charges stipulated on both sides of this contract. I agree with
                    the condition of the vehicle as indicated above and acknowledge its accuracy with my departure. In
                    case of payment by credit card, I authorize, by my signature, to debit my credit card company for
                    the full amount including any damage caused during this rental. I declare being responsible for all
                    traffic violations during the rental period of the vehicle and agree to pay the rental costs plus
                    any additional costs such as damage charges, parking or traffic fines or charges, and administration
                    fees. ∆ηλώνω ότι έχω λάβει αντίγραφο του παρόντος εγγράφου και έχω διαβάσει και αποδέχομαι τους
                    Όρους και Προϋποθέσεις καθώς και τις πρόσθετες επιβαρύνσεις που προβλέπονται από τις δύο πλευρές της
                    παρούσας σύμβασης. Συμφωνώ με την κατάσταση του οχήματος, όπως αναφέρεται παραπάνω και αναγνωρίζω
                    την ακρίβεια του με την αναχώρησή μου. Σε περίπτωση πληρωμής με πιστωτική κάρτα, επιτρέπω με την
                    υπογραφή μου, να χρεωθεί η εταιρεία της πιστωτικής μου κάρτας για το πλήρες ποσό,
                    συμπεριλαμβανομένων τυχόν ζημίες που προκλήθηκαν κατά τη διάρκεια αυτής της ενοικίασης. ∆ηλώνω ότι
                    είμαι υπεύθυνος/η για όλες τις τροχαίες παραβάσεις κατά την περίοδο ενοικίασης του οχήματος

                    Missing fuel will be charged on your return. To avoid incurring fuel charges, you will need to
                    return it with the same amount of fuel as it had when you collected it.
                    Σε περίπτωση διαφοράς καυσίμου θα χρεωθείτε στην επιστροφή του οχήματος. Προς αποφυγή χρεώσεων,
                    επιστρέψτε το όχημα στο ίδιο επίπεδο καυσίμου όπως όταν το παραλάβατε.
                    <br>
                    Excess Amount / Ποσό Ευθύνης:@if (isset($rental)) {{$rental->excess}} @endif
                </div>
            </fieldset>

            {{-- second tab --}}
            <fieldset id="tab021" class="hide">
                <div class="bg-light">
                    <h5 class="text-center mb-4 mt-0 pt-4">Υπογραφή οδηγού</h5>
                </div>
                <div class="px-3">
                    <!-- Modal body 2 -->
                    <div class="modal-body">
                        <div class="wrapper">
                            <canvas id="signature-pad2" class="signature-pad" width="400" height="200"></canvas>
                        </div>
                        <button class='mr-4 btn btn-primary' id="save-png2">Αποθήκευση</button>
                        <button class='mr-2 btn btn-danger' id="delete-png2">Διαγραφή</button>
                        {{-- <button id="save-jpeg">Save as JPEG</button>
                <button id="save-svg">Save as SVG</button> --}}
                        {{-- <button id="draw">Draw</button> --}}
                        {{-- <button id="erase">Erase</button> --}}
                        <button class='mr-2 btn btn-success' id="clear2">Clear</button>

                        @if (isset($rental))
                            @if (file_exists(public_path('../signatures/signatureMain-' . $rental->sequence_number . '.png')))
                                <button class='mr-2 btn btn-secondary' id="see2">Υπογραφή</button>
                            @else
                                <p id="see2"> </p>
                            @endif
                        @endif


                        @if (isset($rental))
                            <input hidden id='myRental2' type='text' value="{{ $rental->id }}">
                            <input hidden id='myRentalSeq2' type='text' value="{{ $rental->sequence_number }}">
                        @endif
                        <br>
                        <br>
                        <p class="alert-success" style='display: none;' id="signatureConfirm2">Saved main
                            successfully
                        </p>
                        <p class="alert-warn" style='display: none;' id="signatureDelete2">Deleted main successfully
                        </p>
                        <img width="400" height="300" style='display: none;' id='see2Image'>
                    </div>
                </div>
                <div class="scroll">
                  <h4> ΌΡΟΙ ΜΙΣΘΩΤΗΡΙΟΥ ΣΥΜΒΟΛΑΙΟΥ</h4>
Με το παρόν η εκμισθώτρια εταιρία, η οποία θα αποκαλείται στο εξής «Εκμισθώτρια», εκμισθώνει προς τον υπογράφοντα, που θα αποκαλείται στο εξής «Μισθωτής», το περιγραφόμενο στη πίσω όψη του παρόντος αυτοκίνητο, που θα αποκαλείται
στο εξής «Το μίσθιο αυτοκίνητο», με τους παρακάτω όρους και συμφωνίες:
<br>
1. Ο μισθωτής παρέλαβε το μίσθιο αυτοκίνητο σε άριστη κατάσταση και το βρήκε της απόλυτης αρεσκείας του. Η εκμισθώτρια ουδεμία ευθύνη φέρει για τυχόν ζημία ή βλάβη του αυτοκινήτου κατά τη διάρκεια της μίσθωσης καθώς και για οποιαδήποτε
θετική ή αποθετική ζημία του μισθωτή από αυτή.
<br>
2. Ο μισθωτής υποχρεούται να επιστρέψει το μίσθιο αυτοκίνητο σε όποια άριστη κατάσταση το παρέλαβε, στο κατάστημα της εκμισθώτριας από όπου το παρέλαβε και κατά τον χρόνο που ορίζεται στο συμβόλαιο. Μετά την πάροδο του συμφωνημένου
χρόνου επιστροφής, η εκμισθώτρια δικαιούται να παραλάβει το μίσθιο αυτοκίνητο και χωρίς την συγκατάθεση του μισθωτή, οποτεδήποτε ή οπουδήποτε και με οποιονδήποτε τρόπο.
<br>
3. Το μίσθιο αυτοκίνητο απαγορεύεται να χρησιμοποιηθεί: α) Για σκοπούς που αντίκεινται στους Ελληνικούς νόμους. β) Για μεταφορά επιβατών ή αντικειμένων έναντι άμεσης ή έμμεσης αμοιβής. γ) Για την ώθηση ή ρυμούλκηση οχήματος. δ) Σε
αγώνες αυτοκινήτου συμπεριλαμβανομένων των διαγωνισμών ταχύτητας, αναγνωρίσεων ολικών ή ειδικών διαδρομών ράλλυ, δοκιμών ανθεκτικότητας και δοκιμών μέγιστης δυνατής ταχύτητας. ε) Από οποιοδήποτε πρόσωπο που βρίσκεται υπό
την επήρεια αλκοόλ ή ναρκωτικών. στ) Από οποιοδήποτε άλλο πρόσωπο πλην των ατόμων που υπογράφουν το παρόν και των ατόμων που αναφέρονται ειδικά σε γραπτή ονομαστική κατάσταση η οποία υποβάλλεται από τον μισθωτή και
συνυπογράφεται από την εκμισθώτρια . ζ)Για ταξίδια εκτός Ελλάδος χωρίς γραπτή συγκατάθεση της εκμισθώτριας, η) Για οποιαδήποτε διαδρομή σε μη ασφαλτοστρωμένη οδό . θ) Το μίσθιο αυτοκίνητο απαγορεύεται να ταξιδέψει ακτοπλοϊκώς
χωρίς την έγγραφη άδεια της εκμισθώτριας. Η παράβαση οποιασδήποτε διατάξεως του παρόντος άρθρου καθιστά τον μισθωτή και οποιονδήποτε άλλο συνυπογράφοντα το μισθωτήριο συμβόλαιο έκπτωτο από όλα τα δικαιώματα που πηγάζουν
από την παρούσα σύμβαση και υπεύθυνο να αποζημιώσει την εκμισθώτρια εξ’ ολοκλήρου για κάθε τυχόν θετική ή αποθετική ζημία της.
<br>
4. Ο μισθωτής έλαβε γνώση ότι εάν δεν παραβιάσει κανέναν όρο απολύτως του παρόντος καλύπτεται από ασφαλιστικές Εταιρίες για τα υπό στοιχεία (γ) και έναντι των εξής κινδύνων: α) Θάνατο ή σωματικές βλάβες τρίτου ή τρίτων προσώπων μη
επιβαινόντων εντός του μισθίου αυτοκινήτου μέχρι ποσού € 1.000.000 συνολικώς κατ’ ανώτατο όριο. β) Υλικές ζημίες τρίτου ή τρίτων προσώπων προκαλούμενες σε πράγματα μη ευρισκόμενα μέσα ή πάνω στο μίσθιο αυτοκίνητο μέχρι συνολικού
ποσού € 1.000.000 κατ’ ανώτατο όριο. γ) Αξία μισθίου αυτοκινήτου έναντι κλοπής, εφόσον έχει αποδεχθεί την ημερησία πρόσθετη χρέωση (TP). δ) Υλικά από σύγκρουση, πυρκαγιά και άλλες ζημίες του μισθίου αυτοκινήτου, για το μέρος το
οποίο οι ζημίες αυτές υπερβαίνουν τα αντιστοίχως απαλλασσόμενα ποσά όπως αυτά προσδιορίζονται από τον εκάστοτε σε ισχύ τιμοκατάλογο της Εταιρίας, αντίγραφο του οποίου κατέχει και προσεκτικά διάβασε ο μισθωτής. Οι καλύψεις των
ζημιών αυτών ισχύουν εφόσον ο μισθωτής έχει αποδεχθεί με την μονογραφή του στις σχετικές στήλες της πίσω σελίδας του παρόντος μισθωτηρίου συμβολαίου την χρέωση των προβλεπόμενων ποσών για τον σκοπό αυτό.
<br>
5. Ο μισθωτής περαιτέρω συμφωνεί να προστατεύει τα συμφέροντα της εκμισθώτριας και της ασφαλιστικής σε περίπτωση ατυχήματος κατά την διάρκεια της μισθώσεως: α) Υποβάλλοντας αμέσως λεπτομερή αναφορά τηλεφωνικώς στο πλησιέστερο
κατάστημα της εκμισθώτριας και στη συνέχεια εγγράφως το ταχύτερο δυνατό. β) Ειδοποιώντας αμέσως τις αστυνομικές αρχές προς εξακρίβωση της υπαιτιότητας τρίτου και την περίθαλψη των τυχόν τραυματιών. γ) Μη παραδεχόμενος υπαιτιότητα
ή ενοχή. δ) Λαμβάνοντας ονόματα και διευθύνσεις προσώπων και μαρτύρων που σχετίζονται με το ατύχημα.
<br>
6. Εάν ο μισθωτής ζητήσει την προσφερόμενη από την εκμισθώτρια ειδική και περιορισμένη ασφάλιση οδηγού και επιβαινόντων, αποδεχθεί την σχετική επιβάρυνση μονογραφώντας στην αντίστοιχη θέση της πρώτης και τελευταίας σελίδας του
παρόντος, θα καλύπτεται από τους όρους και για τα ποσά που αναφέρονται στο ειδικό ασφαλιστικό έντυπο, αντίτυπο του οποίου κατέχει και προσεκτικά διάβασε.
<br>
7. Ο μισθωτής υποχρεούται να καταβάλλει στην εκμισθώτρια: α) Την ημερησία πάγια επιβάρυνση για τη διάρκεια της μίσθωσης. β) Την χιλιομετρική επιβάρυνση για τα χιλιόμετρα που διανύθηκαν από το μίσθιο αυτοκίνητο κατά την διάρκεια
της μίσθωσης με βάση της τιμής μονάδος που καθορίζεται ανά χιλιόμετρο. Τα διανυθέντα χιλιόμετρα προσδιορίζονται από το μετρητή χιλιομέτρων (κοντέρ). Σε περίπτωση μη λειτουργίας του, η χρέωση γίνεται με βάση τις χιλιομετρικές αποστάσεις
του ταξιδιού που πραγματοποιήθηκε. γ) Την αξία της βενζίνης που καταναλώθηκε κατά την διάρκεια της μισθώσεως. δ) Την ειδική ημερήσια πρόσθετη επιβάρυνση για την κάλυψη των ποσών που απαλλάσσονται της ασφάλισης (SCDW) και
την ειδική επιβάρυνση για την αναφερόμενη στη σχετική θέση περιορισμένη ιατροφαρμακευτική ασφάλιση του οδηγού και των επιβαινόντων (PAI) εφόσον έχει αποδεχθεί όλες ή μία από αυτές, με την μονογραφή στη σχετική στήλη στην μπροστινή
και πίσω σελίδα του παρόντος. ε) Την πρόσθετη επιβάρυνση για την παραλαβή ή παράδοση του μισθίου αυτοκινήτου καθώς και την επιβάρυνση για την επιστροφή αυτού σε διαφορετικό σημείο από αυτό που καθορίζεται στη μπροστινή και
πίσω σελίδα του παρόντος, χωρίς την έγγραφη συγκατάθεση της εκμισθώτριας, επιβάρυνση η οποία αναφέρεται στον τιμοκατάλογο της εκμισθώτριας που είναι σε ισχύ. στ) Τους φόρους και τα τέλη υπηρεσιών για τις επιβαρύνσεις που αναφέρονται
στις παραπάνω παραγράφους. ζ) Τα πρόστιμα και τα δικαστικά έξοδα που αφορούν παράνομες σταθμεύσεις, τροχαίες ή άλλες παραβάσεις, που επιβάλλονται στο μίσθιο αυτοκίνητο, στο μισθωτή ή στην εκμισθώτρια κατά τη διάρκεια της μίσθωσης,
καθώς και κάθε αποθετική ζημία της εκμισθώτριας που προέρχεται από διοικητική ποινή που επιβλήθηκε στο μίσθιο αυτοκίνητο κατά την διάρκεια της μίσθωσης. η) Ο μισθωτής υποχρεούται να αποζημιώσει την εκμισθώτρια για κάθε ζημία η
οποία προκλήθηκε στο αυτοκίνητο από οποιαδήποτε αιτία, καθώς και για την ζημία την οποία η εκμισθώτρια υφίσταται σε περίπτωση κλοπής του μισθίου αυτοκινήτου μέχρις του ποσού που αναφέρεται στην στήλη του μισθωτηρίου ή μέχρι το
ποσό της πραγματικής ζημίας του μισθίου κατά τις ασφαλίσεις CDW, πράγμα το οποίο αποδεικνύεται από την ύπαρξη της μονογραφής τους στη θέση «αποδέχομαι» των προαναφερόμενων ασφαλειών.
<br>
8. Ο μισθωτής υποχρεούται να λαμβάνει όλες τις προφυλάξεις για την αποτροπή κλοπής του αυτοκινήτου ευθυνόμενος και για ελαφρά αμέλεια.
<br>
9. Όταν ο μισθωτής παραβεί τον Κ.Ο.Κ. υποχρεούται να καταβάλει το σύνολο της ζημίας, καθώς και ζημίες που μπορούν να συμβούν στις ζάντες, τα ελαστικά και στο κάτω μέρος του αυτοκινήτου, διότι δεν καλύπτονται από κανένα είδος ασφάλειας.
<br>
10. Ο μισθωτής απαλλάσσει με το παρόν την εκμισθώτρια από κάθε ευθύνη για την απώλεια ή καταστροφή οποιουδήποτε περιουσιακού στοιχείου εγκαταλελειμμένου, αποθηκευμένου ή μεταφερόμενου από αυτόν ή από άλλο πρόσωπο εντός ή πάνω
στο μίσθιο αυτοκίνητο, κατά την διάρκεια της μίσθωσης ή μετά την επιστροφή του μισθίου αυτοκινήτου στην εκμισθώτρια. Ο μισθωτής περαιτέρω συμφωνεί να υπερασπιστεί και να αποζημιώσει την εκμισθώτρια για κάθε απαίτηση τρίτων που
βασίζεται ή πηγάζει από αυτή την απώλεια ή καταστροφή.
<br>
11. Οποιαδήποτε συμφωνία που τροποποιεί τους όρους του παρόντος είναι άκυρη εάν δεν συμφωνηθεί εγγράφως.
<br>
12. Η παρούσα συμφωνία διέπεται από τους Ελληνικούς Νόμους και τυχόν διαφορά που θα προκύψει από αυτήν υπάγεται στην αποκλειστική αρμοδιότητα των ελληνικών δικαστηρίων. Η εκμισθώτρια, όμως, έχει το επιλεκτικό δικαίωμα να ασκεί τις
σχετικές αγωγές κατά των μισθωτών, είτε ενώπιον των ελληνικών δικαστηρίων, είτε ενώπιον των δικαστηρίων του τόπου κατοικίας του μισθωτού.
<br>
13. Ο μισθωτής συμφωνεί και αποδέχεται πως όλοι οι ανωτέρω όροι έχουν ισχύ στις περιπτώσεις τόσο κατά τη διάρκεια της μίσθωσης που αρχικά συμφωνήθηκε με την εκμισθώτρια όσο και κατά την αλλαγή του αυτοκινήτου που αρχικά μισθώθηκε
με άλλο.
<br>
14. Ο μισθωτής δηλώνει, τέλος, υπεύθυνα ότι κατέχει κινητή και ακίνητη περιουσία ικανή να καλύψει κάθε υποχρέωση του έναντι της εκμισθώτριας προκύψει από την εν λόγω σύμβαση.
<br>
<br>
<h4>RENTAL AGREEMENT TERMS AND CONDITIONS</h4>
The Company (hereinafter called the “Lessor”) rents hereby to the person signing overleaf (hereinafter called the “Renter”) the vehicle described overleaf and hereinafter called the “Vehicle” under the following terms and conditions. Therefore, the renter
acknowledges and it is agreed that:The renter received the vehicle in good order and condition after he had ascertained that it met his full satisfaction. The Lessor whilst taking all precautions and using its best efforts to prevent such happening shall not be
liable for any mechanical or other damage of the vehicle during the rental period or for any tangible loss sustained by the renter as a result thereof.
<br>
1. The renter is obliged to return the vehicle in exactly the same condition that he received it, also with all the tires, tools, accessories and equipment to the Lessor renting station on the date and time specified overleaf, otherwise he will be charged
accordingly, in any event, should the agreed rental period has expired, the Lessor has the right to collect or retrieve the vehicle even without the renter’s consent at any time, from any place and by any means it may deem suitable.
<br>
2. It is forbidden that the vehicle be used, a) For any illegal transport of goods or for any purpose violating the Greek laws, b) To carry passengers or property for a consideration express or implied, c) For propel or tow any trailer vehicle, d) In motor
events (including racing, pace making, rallying reliability trials and speed testing), e) By any person under the influence of alcohol or drugs, f) By anyone except the person signing this agreement overleaf and any individual included in a list submitted by the
renter and approved by the Lessor’s authorized signature on it, g) Out of Greece without the advance authorization of the Lessor, h) On no asphalt coated roads, i) The car is prohibited to travel coastwise without the written authorization of the Lessor. The
renter and any other person who signed the respective spaces overleaf their rights resulting from this rental agreement and are fully responsible to indemnify the Lessor for any tangible or intangible loss it may sustain if any of the above clauses of this article
3 is violated.
<br>
3. The renter acknowledges that so long as no term and condition of this agreement has been violated during the rental period (specifically the terms and conditions described in the article 3 above) he is covered by the Lessor’s insurance company
below against: a) Death and bodily injuries of third parties or a maximum amount of € 1.000.000 for all persons collectively, b) Material damages of property for a maximum amount of € 1.000.000, c) Total value of the rental car against theft, provided that
the renter has accepted the extra daily charge (TP=Theft protection), d) Collision and fire damages of the rented car to the extent these damages exceed the amounts excluded as these amounts are defined in the prevailing rate folder of the Lessor copy of
which the renter has obtained and carefully read.
<br>
4. The renter further agrees to protect the interests of the Lessor and its insurance company in case of accident during the rental by: a) Giving immediately a detailed report on the telephone to the nearest station of the Lessor followed by a written one
as soon as feasible, b) Notifying the police immediately if another party’s guilt has to be ascertained or if injured people have to be taken care of, c) Not admitting liability or guilt, d) Obtaining names and addresses of parties involved and witnesses.
<br>
5. Should the renter accept the terms and conditions of the special and limited personal accident insurance policy offered by the Lessor and should be he elect to pay the relative charge by initialing the respective space overleaf he shall be covered by
the terms and conditions and for the amounts described in the special Personal Accident Insurance brochure, copy of which he obtained are carefully read.
<br>
6. The rental is personally liable to pay the Lessor on demand: a) The daily time charge for all the days of rental, b) The kilometer charge computed at the rate specified overleaf for the kilometers covered by the vehicle during the rental period. The
number of kilometers covered by the vehicle shall be determined by reading the odometer installed by the manufacturer. If the odometer rails the kilometer charge be made in accordance with the road map distances of the journey travelled, c) The value of
the gasoline consumed during the rental period, d) The special daily additional charges which (i) wave the deductible amounts of the insurance policy over collision fire or other damages (CDW=Collision Damage Waiver), (ii) cover body injuries or death of
the renter and his co-passengers as per the specially and limited paragraph overleaf (PAI=Personal Accident Insurance), (iii) waive the client’s liability against the Lessor in case of theft of the rented car (TP) provided that he had accepted all or anyone of
the charges his acceptance being evidenced by his initials in the respective spaces provided overleaf. Renters electing to pay the additional charges for SCDW, are released from any obligation to pay for collision fire or other damages of the rental vehicle
provided that such damages were not due to a violation of any of the provision of the EU Traffic Law. Also damages to tires, rims and the undercarriage are not covered by insurances. If the renter does not elect to pay the additional daily charges referred in
above clauses (i) and (iii) of paragraph (d) of this article 7, he is responsible to pay to the Lessor to the extent of the respective deductible amount any damage or wear and tear of the vehicle be it accidental or not. In any case the client refuses the above
optional insurance coverage the Lessor has the right to collect from him the respective deductible amounts upon commencement of the Rental. Regardless whether comprehensive car insurance has been taken out or not the client renter is liable for all
damages caused to the underside of the vehicle: including wheels and tires if provision (h) of clause 3 has not been met. e) The additional charges for the delivery or collection of the vehicle as well as the extra for the return of the vehicle to any place other
than the specified overleaf in case the Lessor had not consented to it. Such additional costs will be calculated on the basis of the kilometer charge specified for such cases in the current rate of the Lessor. f) The state taxes and service fees on the total of
the charges described in the above paragraphs (a) – (e). g) All the lines and court costs for illegal parking traffic or other legal violations assessed against the vehicle the renter or the driver or the Lessor during the rental period as well as any intangible loss
of the Lessor resulting from an administrative penalty imposed by the Traffic Police for said violations, h) Renter is liable for all Lessor’s costs for repairing damages caused to the vehicle or for the vehicle’s indicated value (in the event of theft) up to the
amount specified in the bow overleaf or for the actual damages of vehicle whichever amount is smaller. Such liability of the Renter’s is waived if he has accepted on advance the SCDW, optional charges as evidenced solely by his initials in the “accept”
space overleaf.
<br>
7. During the rental period the renter is obliged to take the necessary precautions to prevent the theft of the vehicle and is responsible for even the slightest negligence to this end.
<br>
8. When the renter violates the traffic law, is obliged to pay the total of damage, because it is not covered by any kind of insurance.
<br>
9. The renter hereby releases the Lessor from the loss or damage to any property left stored or transported by the renter or any person in or upon the vehicle before or during the rental or after return the vehicle to the Lessor. The renter further agrees
to protect and indemnify the Lessor against and or for any third-party claim based on or resulting from such loss or damages.
<br>
10. Any additions alterations to the terms and conditions of this agreement shall be null and void unlessin writing by the contracted parties.
<br>
11. This agreement was contracted in accordance with and will be governed by the laws of Greece. Any difference between the contracted parties resulting from this agreement is subject to the exclusive jurisdiction of Greek courts. However the Lessor
has the selective right to file a suit against the renter before the courts of the country where the renter resides.
<br>
12. The renter consents and agrees that he shall be bound by these terms and conditions in relation to any extension of the rental period agreed by the Lessor of in respect of any substitute vehicle rented.
<br>
13. The renter solemnly declares that he possesses enough to cover any financial obligation he may incur against the Lessor as result of the rental agreement.
<br>
<br>
<h4>GDPR</h4>
<p>
DPR: The above data are processed in accordance with the current institutional framework (Generalregulation  679/2016  EU) and contribute to the optimal cooperation between us. Your data beenprotected in an electronic and physical archive for the required by the legislation time. Please make sure you have read our Privacy Policy in this link https://www.prostasiadedomenon.gr/p/carhirethessaloniki/.
Τα ανωτέρω στοιχεία τυγχάνουν επεξεργασίας σύμφωνα με το ισχύον θεσμικό πλαίσιο (Γενικός κανονισμός  679/2016  ΕΕ), με σκοπό τη μεταξύ μας συνεργασία. Τα στοιχεία σας φυλάσσονται σε ηλεκτρονικό και φυσικό αρχείο για το απαιτούμενο από τη νομοθεσία διάστημα. Παρακαλώ βεβαιωθείτε ότι έχετε διαβάσει την Πολιτική Απορρήτου της εταιρείας μας στην ιστοσελίδα  https://www.prostasiadedomenon.gr/p/carhirethessaloniki/ πριν την υπογραφή του παρόντος.
</p>

                </div>
            </fieldset>

            {{-- third tab --}}
            <fieldset id="tab031" class="hide">
                <div class="bg-light">
                    <h5 class="text-center mb-4 mt-0 pt-4">Υπογραφή δεύτερου οδηγού</h5>
                </div>
                <div class="px-3">
                    <!-- Modal body 2 -->
                    <div class="modal-body">
                        <div class="wrapper">
                            <canvas id="signature-pad3" class="signature-pad" width="400"
                                height="200"></canvas>
                        </div>
                        <button class='mr-4 btn btn-primary' id="save-png3">Αποθήκευση</button>
                        <button class='mr-2 btn btn-danger' id="delete-png3">Διαγραφή</button>
                        {{-- <button id="save-jpeg">Save as JPEG</button>
                <button id="save-svg">Save as SVG</button> --}}
                        {{-- <button id="draw">Draw</button> --}}
                        {{-- <button id="erase">Erase</button> --}}
                        <button class='mr-2 btn btn-success' id="clear3">Clear</button>
                        @if (isset($rental))
                            @if (file_exists(public_path('../signatures/signatureSecDriver-' . $rental->sequence_number . '.png')))
                                <button class='mr-2 btn btn-secondary' id="see3">Υπογραφή</button>
                            @else
                                <p id="see3"> </p>
                            @endif
                        @endif

                        @if (isset($rental))
                            <input hidden id='myRental3' type='text' value="{{ $rental->id }}">
                            <input hidden id='myRentalSeq3' type='text' value="{{ $rental->sequence_number }}">
                        @endif
                        <br>
                        <br>
                        <p class="alert-success" style='display: none;' id="signatureConfirm3">Saved second
                            successfully</p>
                        <p class="alert-warn" style='display: none;' id="signatureDelete3">Deleted second
                            successfully</p>
                        <img width="400" height="300" style='display: none;' id='see3Image'>
                    </div>
                </div>
                <div class="scroll">
                  <h4>GDPR</h4>
<p>
DPR: The above data are processed in accordance with the current institutional framework (Generalregulation  679/2016  EU) and contribute to the optimal cooperation between us. Your data beenprotected in an electronic and physical archive for the required by the legislation time. Please make sure you have read our Privacy Policy in this link https://www.prostasiadedomenon.gr/p/carhirethessaloniki/.
Τα ανωτέρω στοιχεία τυγχάνουν επεξεργασίας σύμφωνα με το ισχύον θεσμικό πλαίσιο (Γενικός κανονισμός  679/2016  ΕΕ), με σκοπό τη μεταξύ μας συνεργασία. Τα στοιχεία σας φυλάσσονται σε ηλεκτρονικό και φυσικό αρχείο για το απαιτούμενο από τη νομοθεσία διάστημα. Παρακαλώ βεβαιωθείτε ότι έχετε διαβάσει την Πολιτική Απορρήτου της εταιρείας μας στην ιστοσελίδα  https://www.prostasiadedomenon.gr/p/carhirethessaloniki/ πριν την υπογραφή του παρόντος.
</p>
                </div>
            </fieldset>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                {{-- <button id="print-selected-files" type="button" class="btn btn-primary">Εκτύπωση</button> --}}
            </div>

        </div>
    </div>



    <script>
        //first tab excess
        var canvas1 = document.getElementById('signature-pad1');
        //  var renS1 = document.getElementById('myRentalSeq1').value;
        // signaturePad1.fromDataURL('../signatures/signatureExcess-'.renS1.'.png');
        var signaturePad1 = new SignaturePad(canvas1, {
            backgroundColor: 'rgb(255, 255, 255)', // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
            minWidth: 5,
            maxWidth: 10,
        });
        signaturePad1.addEventListener("beginStroke", () => {
            console.log("Signature started 1");
        }, {
            once: true
        });
        document.getElementById('save-png1').addEventListener('click', function() {
            if (signaturePad1.isEmpty()) {
                return alert("Please provide a signature 1 first.");
            }
            var data1 = signaturePad1.toDataURL('image/png', {
                ratio: 1,
                width: 200,
                height: 100,
                xOffset: 100,
                yOffset: 50
            });
            var ren1 = document.getElementById('myRental1').value;
            $.post("signatureExcess", {
                'file': data1,
                'rental': ren1
            }, function(data) {
                // Display the returned data in browser
                console.log('signature 1 sended');
                $("#signatureConfirm1").removeAttr("style").show().delay(3000).fadeOut();
            });
        });
        document.getElementById('clear1').addEventListener('click', function() {
            signaturePad1.clear();
        });
        document.getElementById('delete-png1').addEventListener('click', function() {
            var ren1 = document.getElementById('myRental1').value;
            $.post("signatureExcDelete", {
                'rental': ren1
            }, function(data) {
                // Display the returned data in browser
                console.log('signature 1 deleted');
                $("#signatureDelete1").removeAttr("style").show().delay(3000).fadeOut();
            });
        });

        document.getElementById('see1').addEventListener('click', function() {
            var ren1 = document.getElementById('myRental1').value;
            $.post("signatureSee1", {
                'rental': ren1
            }, function(data) {
                // Display the returned data in browser
                console.log('signature 1 seen');
                $("#see1Image").attr('src', data);
                $("#see1Image").removeAttr("style").show().delay(3000).fadeOut();
            });
        });
        //end of tab excess

        //second tab main
        var canvas2 = document.getElementById('signature-pad2');
        //  var renS2 = document.getElementById('myRentalSeq2').value;
        // signaturePad2.fromDataURL('../signatures/signatureMain-'.renS2.'.png');
        var signaturePad2 = new SignaturePad(canvas2, {
            backgroundColor: 'rgb(255, 255, 255)', // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
            minWidth: 5,
            maxWidth: 10,
        });
        signaturePad2.addEventListener("beginStroke", () => {
            console.log("Signature started 2");
        }, {
            once: true
        });
        document.getElementById('save-png2').addEventListener('click', function() {
            if (signaturePad2.isEmpty()) {
                return alert("Please provide a signature 2 first.");
            }
            var data2 = signaturePad2.toDataURL('image/png', {
                ratio: 1,
                width: 200,
                height: 100,
                xOffset: 100,
                yOffset: 50
            });
            var ren2 = document.getElementById('myRental2').value;
            $.post("signatureMain", {
                'file': data2,
                'rental': ren2
            }, function(data) {
                // Display the returned data in browser
                console.log('signature 2 sended');
                $("#signatureConfirm2").removeAttr("style").show().delay(3000).fadeOut();
            });
        });
        document.getElementById('clear2').addEventListener('click', function() {
            signaturePad2.clear();
        });
        document.getElementById('delete-png2').addEventListener('click', function() {
            var ren2 = document.getElementById('myRental2').value;
            $.post("signatureMDelete", {
                'rental': ren2
            }, function(data) {
                // Display the returned data in browser
                console.log('signature 2 deleted');
                $("#signatureDelete2").removeAttr("style").show().delay(3000).fadeOut();
            });
        });

        document.getElementById('see2').addEventListener('click', function() {
            var ren2 = document.getElementById('myRental2').value;
            $.post("signatureSee2", {
                'rental': ren2
            }, function(data) {
                // Display the returned data in browser
                console.log('signature 2 seen');
                $("#see2Image").attr('src', data);
                $("#see2Image").removeAttr("style").show().delay(3000).fadeOut();
            });
        });
        //end of tab main

        //third tab secondDriver-signature
        var canvas3 = document.getElementById('signature-pad3');
        //  var renS3 = document.getElementById('myRentalSeq3').value;
        // signaturePad3.fromDataURL('../signatures/signatureSecDriver-'.renS3.'.png');
        var signaturePad3 = new SignaturePad(canvas3, {
            backgroundColor: 'rgb(255, 255, 255)', // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
            minWidth: 5,
            maxWidth: 10,
        });
        signaturePad3.addEventListener("beginStroke", () => {
            console.log("Signature started 3");
        }, {
            once: true
        });
        document.getElementById('save-png3').addEventListener('click', function() {
            if (signaturePad3.isEmpty()) {
                return alert("Please provide a signature 3 first.");
            }
            var data3 = signaturePad3.toDataURL('image/png', {
                ratio: 1,
                width: 200,
                height: 100,
                xOffset: 100,
                yOffset: 50
            });
            var ren3 = document.getElementById('myRental3').value;
            $.post("signatureSecDriver", {
                'file': data3,
                'rental': ren3
            }, function(data) {
                // Display the returned data in browser
                console.log('signature 3 sended');
                $("#signatureConfirm3").removeAttr("style").show().delay(3000).fadeOut();
            });
        });
        document.getElementById('clear3').addEventListener('click', function() {
            signaturePad3.clear();
        });
        document.getElementById('delete-png3').addEventListener('click', function() {
            var ren3 = document.getElementById('myRental3').value;
            $.post("signatureSecDelete", {
                'rental': ren3
            }, function(data) {
                // Display the returned data in browser
                console.log('signature 3 deleted');
                $("#signatureDelete3").removeAttr("style").show().delay(3000).fadeOut();
            });
        });
        document.getElementById('see3').addEventListener('click', function() {
            var ren3 = document.getElementById('myRental3').value;
            $.post("signatureSee3", {
                'rental': ren3
            }, function(data) {
                // Display the returned data in browser
                console.log('signature 3 seen');
                $("#see3Image").attr('src', data);
                $("#see3Image").removeAttr("style").show().delay(3000).fadeOut();
            });
        });
        //end of tab secondDriver-signature


        // for modal tabs
        $(document).ready(function() {

            $(".tabs").click(function() {

                $(".tabs").removeClass("active");
                $(".tabs h6").removeClass("font-weight-bold");
                $(".tabs h6").addClass("text-muted");
                $(this).children("h6").removeClass("text-muted");
                $(this).children("h6").addClass("font-weight-bold");
                $(this).addClass("active");

                current_fs = $(".active");

                next_fs = $(this).attr('id');
                next_fs = "#" + next_fs + "1";

                $("fieldset").removeClass("show");
                $(next_fs).addClass("show");

                current_fs.animate({}, {
                    step: function() {
                        current_fs.css({
                            'display': 'none',
                            'position': 'relative'
                        });
                        next_fs.css({
                            'display': 'block'
                        });
                    }
                });
            });

        });
    </script>

</div>
