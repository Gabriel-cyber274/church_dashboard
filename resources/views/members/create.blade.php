<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Member</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        body {
            background-color: #353535;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background: #ffffff;
            border-bottom: 1px solid #e9ecef;
            padding: 1.25rem 1.5rem;
        }

        .card-header h4 {
            margin: 0;
            font-weight: 600;
            color: #212529;
        }

        .card-body {
            padding: 1.75rem;
        }

        label {
            font-weight: 500;
            color: #495057;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            min-height: 44px;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
        }

        .required::after {
            content: " *";
            color: #dc3545;
        }

        /* Select2 Styling */
        .select2-container--default .select2-selection--multiple {
            min-height: 44px;
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 6px;
        }

        .select2-selection__choice {
            background-color: #0d6efd;
            border: none;
            color: #fff;
            border-radius: 6px;
            padding: 3px 8px;
            font-size: 0.85rem;
        }

        .select2-selection__choice__remove {
            color: #fff;
            margin-right: 6px;
        }

        .form-footer {
            border-top: 1px solid #e9ecef;
            padding-top: 1.25rem;
            margin-top: 2rem;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        .optional {
            font-weight: normal;
            color: #6c757d;
        }

        .optional::after {
            content: " (optional)";
            color: #6c757d;
            font-weight: normal;
        }
    </style>
</head>

<body>
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="d-flex justify-content-center">
                    <img src="{{ asset('images/logo.png') }}" height="100" alt="Church Logo" class="navbar-logo">
                </div>


                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Register as a Member</h4>
                        <button class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#qrModal">
                            QR Code
                        </button>
                    </div>

                    <div class="card-body">

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('members.store') }}">
                            @csrf

                            <div class="row g-3">
                                <!-- First Name -->
                                <div class="col-md-6">
                                    <label class="required">First Name</label>
                                    <input type="text" class="form-control" name="first_name"
                                        value="{{ old('first_name') }}" required>
                                </div>

                                <!-- Last Name -->
                                <div class="col-md-6">
                                    <label class="required">Last Name</label>
                                    <input type="text" class="form-control" name="last_name"
                                        value="{{ old('last_name') }}" required>
                                </div>

                                <!-- Email -->
                                <div class="col-md-12">
                                    <label class="optional">Email Address</label>
                                    <input type="email" class="form-control" name="email"
                                        value="{{ old('email') }}">
                                    <small class="text-muted">Optional</small>
                                </div>

                                <!-- Date of Birth -->
                                <div class="col-md-6">
                                    <label class="required">Date of Birth</label>
                                    <input type="date" class="form-control" name="date_of_birth"
                                        value="{{ old('date_of_birth') }}" required>
                                </div>

                                <!-- Phone Number -->
                                <div class="col-md-6">
                                    <label class="optional">Phone Number</label>
                                    <input type="tel" class="form-control" name="phone_number"
                                        value="{{ old('phone_number') }}">
                                    <small class="text-muted">Optional</small>
                                </div>

                                <!-- Country -->
                                <div class="col-md-4">
                                    <label class="required">Country of Residence</label>
                                    <select class="form-select" id="country" name="country" required>
                                        <option value="">Loading countries...</option>
                                    </select>
                                </div>

                                <!-- State -->
                                <div class="col-md-4" id="state-wrapper">
                                    <label class="required">State of Residence</label>
                                    <select class="form-select" id="state" name="state" disabled>
                                        <option value="">Select country first</option>
                                    </select>
                                </div>

                                <!-- City -->
                                <div class="col-md-4" id="city-wrapper">
                                    <label class="required">City of Residence</label>
                                    <select class="form-select" id="city" name="city" disabled>
                                        <option value="">Select state first</option>
                                    </select>
                                </div>

                                <!-- Address -->
                                <div class="col-md-12">
                                    <label class="required">Address</label>
                                    <textarea class="form-control" rows="2" name="address" required>{{ old('address') }}</textarea>
                                </div>

                                <!-- Gender -->
                                <div class="col-md-6">
                                    <label class="required">Gender</label>
                                    <select class="form-select" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male" @selected(old('gender') === 'male')>Male</option>
                                        <option value="female" @selected(old('gender') === 'female')>Female</option>
                                    </select>
                                </div>

                                <!-- Marital Status -->
                                <div class="col-md-6">
                                    <label class="required">Marital Status</label>
                                    <select class="form-select" name="marital_status" required>
                                        <option value="">Select Status</option>
                                        <option value="single" @selected(old('marital_status') === 'single')>Single</option>
                                        <option value="married" @selected(old('marital_status') === 'married')>Married</option>
                                        <option value="divorced" @selected(old('marital_status') === 'divorced')>Divorced</option>
                                        <option value="widowed" @selected(old('marital_status') === 'widowed')>Widowed</option>
                                    </select>
                                </div>

                                <!-- Departments -->
                                <div class="col-md-12">
                                    <label class="optional">Departments</label>
                                    <select class="form-select select2" name="departments[]" multiple>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}" @selected(is_array(old('departments')) && in_array($department->id, old('departments')))>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Optional - you may select more than one
                                        department</small>
                                </div>
                            </div>

                            <div class="form-footer">
                                <button type="reset" class="btn btn-light">Clear</button>
                                <button type="submit" class="btn btn-primary px-4">Submit</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- QR Code Modal -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Page QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="qrcode" class="d-flex justify-content-center mb-3"></div>
                    <button class="btn btn-success" onclick="downloadQRCode()">Download QR Code</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>

    {{-- <script>
        const countryEl = document.getElementById('country');
        const stateWrapper = document.getElementById('state-wrapper');
        const cityWrapper = document.getElementById('city-wrapper');

        /* Replace select with input */
        function replaceWithInput(wrapper, name, label) {
            wrapper.innerHTML = `
        <label class="required">${label}</label>
        <input type="text" class="form-control" name="${name}" required>
    `;
        }

        /* Load countries */
        fetch('https://countriesnow.space/api/v0.1/countries')
            .then(res => res.json())
            .then(res => {
                countryEl.innerHTML = '<option value="">Select Country</option>';
                res.data.forEach(c => {
                    countryEl.innerHTML += `<option value="${c.country}">${c.country}</option>`;
                });
            });

        /* Country → State */
        countryEl.addEventListener('change', function() {
            stateWrapper.innerHTML = `
        <label class="required">State</label>
        <select class="form-select" id="state" name="state"></select>
    `;
            cityWrapper.innerHTML = `
        <label class="required">City</label>
        <select class="form-select" id="city" name="city" disabled></select>
    `;

            if (!this.value) return;

            const stateEl = document.getElementById('state');

            fetch('https://countriesnow.space/api/v0.1/countries/states', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        country: this.value
                    })
                })
                .then(res => res.json())
                .then(res => {
                    if (!res.data || res.data.states.length === 0) {
                        replaceWithInput(stateWrapper, 'state', 'State');
                        replaceWithInput(cityWrapper, 'city', 'City');
                        return;
                    }

                    stateEl.innerHTML = '<option value="">Select State</option>';
                    res.data.states.forEach(s => {
                        stateEl.innerHTML += `<option value="${s.name}">${s.name}</option>`;
                    });

                    /* State → City */
                    stateEl.addEventListener('change', function() {
                        const cityEl = document.getElementById('city');
                        cityEl.disabled = true;
                        cityEl.innerHTML = '<option>Loading...</option>';

                        fetch('https://countriesnow.space/api/v0.1/countries/state/cities', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    country: countryEl.value,
                                    state: this.value
                                })
                            })
                            .then(res => res.json())
                            .then(res => {
                                if (!res.data || res.data.length === 0) {
                                    replaceWithInput(cityWrapper, 'city', 'City');
                                    return;
                                }

                                cityEl.disabled = false;
                                cityEl.innerHTML = '<option value="">Select City</option>';
                                res.data.forEach(city => {
                                    cityEl.innerHTML +=
                                        `<option value="${city}">${city}</option>`;
                                });
                            });
                    });
                });
        });
    </script> --}}


    <script>
        const nigeriaData = [
            "Abia", "Adamawa", "Akwa Ibom", "Anambra", "Bauchi", "Bayelsa", "Benue", "Borno",
            "Cross River", "Delta", "Ebonyi", "Edo", "Ekiti", "Enugu", "Gombe", "Imo",
            "Jigawa", "Kaduna", "Kano", "Katsina", "Kebbi", "Kogi", "Kwara", "Lagos",
            "Nasarawa", "Niger", "Ogun", "Ondo", "Osun", "Oyo", "Plateau", "Rivers",
            "Sokoto", "Taraba", "Yobe", "Zamfara", "Federal Capital Territory (FCT)"
        ];

        const nigeriaLGAs = {
            "Abia": ["Aba North", "Aba South", "Arochukwu", "Bende", "Ikwuano", "Isiala Ngwa North",
                "Isiala Ngwa South", "Isuikwuato", "Obi Ngwa", "Ohafia", "Osisioma Ngwa", "Ugwunagbo", "Ukwa East",
                "Ukwa West", "Umuahia North", "Umuahia South", "Umu Nneochi"
            ],
            "Adamawa": ["Demsa", "Fufore", "Ganye", "Girei", "Gombi", "Guyuk", "Hong", "Jada", "Lamurde", "Madagali",
                "Maiha", "Mayo Belwa", "Michika", "Mubi North", "Mubi South", "Numan", "Shelleng", "Song", "Toungo",
                "Yola North", "Yola South"
            ],
            "Akwa Ibom": ["Abak", "Eastern Obolo", "Eket", "Esit Eket", "Essien Udim", "Etim Ekpo", "Etinan", "Ibeno",
                "Ibesikpo Asutan", "Ibiono Ibom", "Ika", "Ikono", "Ikot Abasi", "Ikot Ekpene", "Ini", "Itu", "Mbo",
                "Mkpat Enin", "Nsit Atai", "Nsit Ibom", "Nsit Ubium", "Obot Akara", "Okobo", "Onna", "Oron",
                "Oruk Anam", "Udung Uko", "Ukanafun", "Uruan", "Urue-Offong/Oruko", "Uyo"
            ],
            "Anambra": ["Aguata", "Anambra East", "Anambra West", "Anaocha", "Awka North", "Awka South", "Ayamelum",
                "Dunukofia", "Ekwusigo", "Idemili North", "Idemili South", "Ihiala", "Njikoka", "Nnewi North",
                "Nnewi South", "Ogbaru", "Onitsha North", "Onitsha South", "Orumba North", "Orumba South", "Oyi"
            ],
            "Bauchi": ["Alkaleri", "Bauchi", "Bogoro", "Damban", "Darazo", "Dass", "Gamawa", "Ganjuwa", "Giade",
                "Itas/Gadau", "Jama'are", "Katagum", "Kirfi", "Misau", "Ningi", "Shira", "Tafawa Balewa", "Toro",
                "Warji", "Zaki"
            ],
            "Bayelsa": ["Brass", "Ekeremor", "Kolokuma/Opokuma", "Nembe", "Ogbia", "Sagbama", "Southern Ijaw",
                "Yenagoa"
            ],
            "Benue": ["Ado", "Agatu", "Apa", "Buruku", "Gboko", "Guma", "Gwer East", "Gwer West", "Katsina-Ala",
                "Konshisha", "Kwande", "Logo", "Makurdi", "Obi", "Ogbadibo", "Ohimini", "Oju", "Okpokwu", "Otukpo",
                "Tarka", "Ukum", "Ushongo", "Vandeikya"
            ],
            "Borno": ["Abadam", "Askira/Uba", "Bama", "Bayo", "Biu", "Chibok", "Damboa", "Dikwa", "Gubio", "Guzamala",
                "Gwoza", "Hawul", "Jere", "Kaga", "Kala/Balge", "Konduga", "Kukawa", "Kwaya Kusar", "Mafa",
                "Magumeri", "Maiduguri", "Marte", "Mobbar", "Monguno", "Ngala", "Nganzai", "Shani"
            ],
            "Cross River": ["Abi", "Akamkpa", "Akpabuyo", "Bakassi", "Bekwarra", "Biase", "Boki", "Calabar Municipal",
                "Calabar South", "Etung", "Ikom", "Obanliku", "Obubra", "Obudu", "Odukpani", "Ogoja", "Yakuur",
                "Yala"
            ],
            "Delta": ["Aniocha North", "Aniocha South", "Bomadi", "Burutu", "Ethiope East", "Ethiope West",
                "Ika North East", "Ika South", "Isoko North", "Isoko South", "Ndokwa East", "Ndokwa West", "Okpe",
                "Oshimili North", "Oshimili South", "Patani", "Sapele", "Udu", "Ughelli North", "Ughelli South",
                "Ukwuani", "Uvwie", "Warri North", "Warri South", "Warri South West"
            ],
            "Ebonyi": ["Abakaliki", "Afikpo North", "Afikpo South", "Ebonyi", "Ezza North", "Ezza South", "Ikwo",
                "Ishielu", "Ivo", "Izzi", "Ohaozara", "Ohaukwu", "Onicha"
            ],
            "Edo": ["Akoko-Edo", "Egor", "Esan Central", "Esan North-East", "Esan South-East", "Esan West",
                "Etsako Central", "Etsako East", "Etsako West", "Igueben", "Ikpoba Okha", "Oredo", "Orhionmwon",
                "Ovia North-East", "Ovia South-West", "Owan East", "Owan West", "Uhunmwonde"
            ],
            "Ekiti": ["Ado Ekiti", "Efon", "Ekiti East", "Ekiti South-West", "Ekiti West", "Emure", "Gbonyin",
                "Ido Osi", "Ijero", "Ikere", "Ikole", "Ilejemeje", "Irepodun/Ifelodun", "Ise/Orun", "Moba", "Oye"
            ],
            "Enugu": ["Aninri", "Awgu", "Enugu East", "Enugu North", "Enugu South", "Ezeagu", "Igbo Etiti",
                "Igbo Eze North", "Igbo Eze South", "Isi Uzo", "Nkanu East", "Nkanu West", "Nsukka", "Oji River",
                "Udenu", "Udi", "Uzo Uwani"
            ],
            "Gombe": ["Akko", "Balanga", "Billiri", "Dukku", "Funakaye", "Gombe", "Kaltungo", "Kwami", "Nafada",
                "Shongom", "Yamaltu/Deba"
            ],
            "Imo": ["Aboh Mbaise", "Ahiazu Mbaise", "Ehime Mbano", "Ezinihitte", "Ideato North", "Ideato South",
                "Ihitte/Uboma", "Ikeduru", "Isiala Mbano", "Isu", "Mbaitoli", "Ngor Okpala", "Njaba", "Nkwerre",
                "Nwangele", "Obowo", "Oguta", "Ohaji/Egbema", "Okigwe", "Orlu", "Orsu", "Oru East", "Oru West",
                "Owerri Municipal", "Owerri North", "Owerri West", "Unuimo"
            ],
            "Jigawa": ["Auyo", "Babura", "Biriniwa", "Birnin Kudu", "Buji", "Dutse", "Gagarawa", "Garki", "Gumel",
                "Guri", "Gwaram", "Gwiwa", "Hadejia", "Jahun", "Kafin Hausa", "Kaugama", "Kazaure", "Kiri Kasama",
                "Kiyawa", "Maigatari", "Malam Madori", "Miga", "Ringim", "Roni", "Sule Tankarkar", "Taura",
                "Yankwashi"
            ],
            "Kaduna": ["Birnin Gwari", "Chikun", "Giwa", "Igabi", "Ikara", "Jaba", "Jema'a", "Kachia", "Kaduna North",
                "Kaduna South", "Kagarko", "Kajuru", "Kaura", "Kauru", "Kubau", "Kudan", "Lere", "Makarfi",
                "Sabon Gari", "Sanga", "Soba", "Zangon Kataf", "Zaria"
            ],
            "Kano": ["Ajingi", "Albasu", "Bagwai", "Bebeji", "Bichi", "Bunkure", "Dala", "Dambatta", "Dawakin Kudu",
                "Dawakin Tofa", "Doguwa", "Fagge", "Gabasawa", "Garko", "Garun Mallam", "Gaya", "Gezawa", "Gwale",
                "Gwarzo", "Kabo", "Kano Municipal", "Karaye", "Kibiya", "Kiru", "Kumbotso", "Kunchi", "Kura",
                "Madobi", "Makoda", "Minjibir", "Nasarawa", "Rano", "Rimin Gado", "Rogo", "Shanono", "Sumaila",
                "Takai", "Tarauni", "Tofa", "Tsanyawa", "Tudun Wada", "Ungogo", "Warawa", "Wudil"
            ],
            "Katsina": ["Bakori", "Batagarawa", "Batsari", "Baure", "Bindawa", "Charanchi", "Dandume", "Danja",
                "Dan Musa", "Daura", "Dutsi", "Dutsin Ma", "Faskari", "Funtua", "Ingawa", "Jibia", "Kafur", "Kaita",
                "Kankara", "Kankia", "Katsina", "Kurfi", "Kusada", "Mai'Adua", "Malumfashi", "Mani", "Mashi",
                "Matazu", "Musawa", "Rimi", "Sabuwa", "Safana", "Sandamu", "Zango"
            ],
            "Kebbi": ["Aleiro", "Arewa Dandi", "Argungu", "Augie", "Bagudo", "Birnin Kebbi", "Bunza", "Dandi", "Fakai",
                "Gwandu", "Jega", "Kalgo", "Koko/Besse", "Maiyama", "Ngaski", "Sakaba", "Shanga", "Suru",
                "Wasagu/Danko", "Yauri", "Zuru"
            ],
            "Kogi": ["Adavi", "Ajaokuta", "Ankpa", "Bassa", "Dekina", "Ibaji", "Idah", "Igalamela Odolu", "Ijumu",
                "Kabba/Bunu", "Kogi", "Lokoja", "Mopa Muro", "Ofu", "Ogori/Magongo", "Okehi", "Okene", "Olamaboro",
                "Omala", "Yagba East", "Yagba West"
            ],
            "Kwara": ["Asa", "Baruten", "Edu", "Ekiti", "Ifelodun", "Ilorin East", "Ilorin South", "Ilorin West",
                "Irepodun", "Isin", "Kaiama", "Moro", "Offa", "Oke Ero", "Oyun", "Pategi"
            ],
            "Lagos": ["Agege", "Ajeromi-Ifelodun", "Alimosho", "Amuwo-Odofin", "Apapa", "Badagry", "Epe", "Eti Osa",
                "Ibeju-Lekki", "Ifako-Ijaiye", "Ikeja", "Ikorodu", "Kosofe", "Lagos Island", "Lagos Mainland",
                "Mushin", "Ojo", "Oshodi-Isolo", "Shomolu", "Surulere"
            ],
            "Nasarawa": ["Akwanga", "Awe", "Doma", "Karu", "Keana", "Keffi", "Kokona", "Lafia", "Nasarawa",
                "Nasarawa Egon", "Obi", "Toto", "Wamba"
            ],
            "Niger": ["Agaie", "Agwara", "Bida", "Borgu", "Bosso", "Chanchaga", "Edati", "Gbako", "Gurara", "Katcha",
                "Kontagora", "Lapai", "Lavun", "Magama", "Mariga", "Mashegu", "Mokwa", "Munya", "Paikoro", "Rafi",
                "Rijau", "Shiroro", "Suleja", "Tafa", "Wushishi"
            ],
            "Ogun": ["Abeokuta North", "Abeokuta South", "Ado-Odo/Ota", "Egbado North", "Egbado South", "Ewekoro",
                "Ifo", "Ijebu East", "Ijebu North", "Ijebu North East", "Ijebu Ode", "Ikenne", "Imeko Afon",
                "Ipokia", "Obafemi Owode", "Odeda", "Odogbolu", "Ogun Waterside", "Remo North", "Shagamu"
            ],
            "Ondo": ["Akoko North-East", "Akoko North-West", "Akoko South-East", "Akoko South-West", "Akure North",
                "Akure South", "Ese Odo", "Idanre", "Ifedore", "Ilaje", "Ile Oluji/Okeigbo", "Irele", "Odigbo",
                "Okitipupa", "Ondo East", "Ondo West", "Ose", "Owo"
            ],
            "Osun": ["Atakunmosa East", "Atakunmosa West", "Aiyedaade", "Aiyedire", "Boluwaduro", "Boripe", "Ede North",
                "Ede South", "Egbedore", "Ejigbo", "Ife Central", "Ife East", "Ife North", "Ife South", "Ifedayo",
                "Ifelodun", "Ila", "Ilesa East", "Ilesa West", "Irepodun", "Irewole", "Isokan", "Iwo", "Obokun",
                "Odo Otin", "Ola Oluwa", "Olorunda", "Oriade", "Orolu", "Osogbo"
            ],
            "Oyo": ["Afijio", "Akinyele", "Atiba", "Atisbo", "Egbeda", "Ibadan North", "Ibadan North-East",
                "Ibadan North-West", "Ibadan South-East", "Ibadan South-West", "Ibarapa Central", "Ibarapa East",
                "Ibarapa North", "Ido", "Irepo", "Iseyin", "Itesiwaju", "Iwajowa", "Kajola", "Lagelu",
                "Ogbomosho North", "Ogbomosho South", "Ogo Oluwa", "Olorunsogo", "Oluyole", "Ona Ara", "Orelope",
                "Ori Ire", "Oyo East", "Oyo West", "Saki East", "Saki West", "Surulere"
            ],
            "Plateau": ["Barkin Ladi", "Bassa", "Bokkos", "Jos East", "Jos North", "Jos South", "Kanam", "Kanke",
                "Langtang South", "Langtang North", "Mangu", "Mikang", "Pankshin", "Qua'an Pan", "Riyom", "Shendam",
                "Wase"
            ],
            "Rivers": ["Abua/Odual", "Ahoada East", "Ahoada West", "Akuku-Toru", "Andoni", "Asari-Toru", "Bonny",
                "Degema", "Eleme", "Emuoha", "Etche", "Gokana", "Ikwerre", "Khana", "Obio/Akpor",
                "Ogba/Egbema/Ndoni", "Ogu/Bolo", "Okrika", "Omuma", "Opobo/Nkoro", "Oyigbo", "Port Harcourt", "Tai"
            ],
            "Sokoto": ["Binji", "Bodinga", "Dange Shuni", "Gada", "Goronyo", "Gudu", "Gwadabawa", "Illela", "Isa",
                "Kebbe", "Kware", "Rabah", "Sabon Birni", "Shagari", "Silame", "Sokoto North", "Sokoto South",
                "Tambuwal", "Tangaza", "Tureta", "Wamako", "Wurno", "Yabo"
            ],
            "Taraba": ["Ardo Kola", "Bali", "Donga", "Gashaka", "Gassol", "Ibi", "Jalingo", "Karim Lamido", "Kurmi",
                "Lau", "Sardauna", "Takum", "Ussa", "Wukari", "Yorro", "Zing"
            ],
            "Yobe": ["Bade", "Bursari", "Damaturu", "Fika", "Fune", "Geidam", "Gujba", "Gulani", "Jakusko", "Karasuwa",
                "Machina", "Nangere", "Nguru", "Potiskum", "Tarmuwa", "Yunusari", "Yusufari"
            ],
            "Zamfara": ["Anka", "Bakura", "Birnin Magaji/Kiyaw", "Bukkuyum", "Bungudu", "Gummi", "Gusau",
                "Kaura Namoda", "Maradun", "Maru", "Shinkafi", "Talata Mafara", "Chafe", "Zurmi"
            ],
            "Federal Capital Territory (FCT)": ["Abaji", "Bwari", "Gwagwalada", "Kuje", "Kwali",
                "Municipal Area Council"
            ]
        };

        const countryEl = document.getElementById('country');
        const stateWrapper = document.getElementById('state-wrapper');
        const cityWrapper = document.getElementById('city-wrapper');

        /* Replace select with input */
        function replaceWithInput(wrapper, name, label) {
            wrapper.innerHTML = `
        <label class="required">${label}</label>
        <input type="text" class="form-control" name="${name}" required>
    `;
        }

        /* Load countries */
        fetch('https://countriesnow.space/api/v0.1/countries')
            .then(res => res.json())
            .then(res => {
                countryEl.innerHTML = '<option value="">Select Country</option>';
                res.data.forEach(c => {
                    countryEl.innerHTML += `<option value="${c.country}">${c.country}</option>`;
                });
            });

        /* Country → State */
        countryEl.addEventListener('change', function() {
            stateWrapper.innerHTML = `
        <label class="required">State</label>
        <select class="form-select" id="state" name="state"></select>
    `;
            cityWrapper.innerHTML = `
        <label class="required">City</label>
        <select class="form-select" id="city" name="city" disabled></select>
    `;

            if (!this.value) return;

            const stateEl = document.getElementById('state');
            stateEl.innerHTML = '<option value="">Select State</option>';

            // Check if selected country is Nigeria
            if (this.value === 'Nigeria') {
                // Use Nigeria data from JSON
                nigeriaData.forEach(state => {
                    stateEl.innerHTML += `<option value="${state}">${state}</option>`;
                });

                stateEl.disabled = false;

                /* State → City (LGA) for Nigeria */
                stateEl.addEventListener('change', function() {
                    const cityEl = document.getElementById('city');
                    cityEl.disabled = true;
                    cityEl.innerHTML = '<option value="">Select City (LGA)</option>';

                    if (this.value && nigeriaLGAs[this.value]) {
                        cityEl.disabled = false;
                        nigeriaLGAs[this.value].forEach(lga => {
                            cityEl.innerHTML += `<option value="${lga}">${lga}</option>`;
                        });
                    }
                }, {
                    once: false
                }); // Reset listener each time

            } else {
                // For non-Nigeria countries, use API
                fetch('https://countriesnow.space/api/v0.1/countries/states', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            country: this.value
                        })
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (!res.data || res.data.states.length === 0) {
                            replaceWithInput(stateWrapper, 'state', 'State');
                            replaceWithInput(cityWrapper, 'city', 'City');
                            return;
                        }

                        res.data.states.forEach(s => {
                            stateEl.innerHTML += `<option value="${s.name}">${s.name}</option>`;
                        });
                        stateEl.disabled = false;

                        /* State → City (for non-Nigeria) */
                        stateEl.addEventListener('change', function() {
                            const cityEl = document.getElementById('city');
                            cityEl.disabled = true;
                            cityEl.innerHTML = '<option>Loading...</option>';

                            fetch('https://countriesnow.space/api/v0.1/countries/state/cities', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        country: countryEl.value,
                                        state: this.value
                                    })
                                })
                                .then(res => res.json())
                                .then(res => {
                                    if (!res.data || res.data.length === 0) {
                                        replaceWithInput(cityWrapper, 'city', 'City');
                                        return;
                                    }

                                    cityEl.disabled = false;
                                    cityEl.innerHTML = '<option value="">Select City</option>';
                                    res.data.forEach(city => {
                                        cityEl.innerHTML +=
                                            `<option value="${city}">${city}</option>`;
                                    });
                                });
                        }, {
                            once: false
                        }); // Reset listener each time
                    });
            }
        });
    </script>

    <script>
        $(function() {
            $('.select2').select2({
                placeholder: "Select departments (optional)",
                width: '100%'
            });
        });

        let qrGenerated = false;
        const modal = document.getElementById('qrModal');

        modal.addEventListener('shown.bs.modal', function() {
            if (!qrGenerated) {
                new QRCode(document.getElementById("qrcode"), {
                    text: window.location.href,
                    width: 220,
                    height: 220,
                });
                qrGenerated = true;
            }
        });

        function downloadQRCode() {
            const qrCanvas = document.querySelector('#qrcode canvas');
            if (!qrCanvas) return;

            const qrImage = qrCanvas.toDataURL("image/png");
            const link = document.createElement('a');
            link.href = qrImage;
            link.download = 'page-qrcode.png';
            link.click();
        }
    </script>
</body>

</html>
