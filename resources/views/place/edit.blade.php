@extends('layouts.master')

@section('title', 'Edit advertiser place')

@section('content')

    <div class="container">
        <div class="row">

            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">

                <div>
                    <form action="{{ route('places.update', $id) }}" method="PATCH" class="nau-form" id="createPlaceForm" target="_top">

                        <p class="title" style="margin-top: 32px;">Edit advertiser place</p>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">Name *</span>
                                    <input name="name" value="{{ $name }}" class="formData">
                                </label>
                            </p>
                            <p class="hint">Please, enter the Place name (minimum 3 characters).</p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">Description</span>
                                    <textarea name="description" class="formData">{{ $description }}</textarea>
                                </label>
                            </p>
                            <p class="hint">Please, enter the Place description.</p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">About</span>
                                    <textarea name="about" class="formData">{{ $about }}</textarea>
                                </label>
                            </p>
                            <p class="hint">Please, enter the information About Place.</p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">Address</span>
                                    <input name="address" value="{{ $address }}" class="formData">
                                </label>
                            </p>
                            <p class="hint">Please, enter the Place address.</p>
                        </div>

                        <div class="control-box">
                            <p class="control-select valid-not-empty">
                                <label>
                                    <span class="input-label">Place category *</span>
                                    <select id="place_category" name="category" class="formData"></select>
                                </label>
                            </p>
                            <p class="hint">Please, select the category.</p>
                        </div>

                        <p><strong>Retail Type *</strong></p>
                        <div class="control-box" id="place_retailtype">
                        </div>
                        <p class="hint">Please, select one or more Retail Type.</p>

                        <p><strong>Specialties</strong></p>
                        <div class="control-box" id="place_specialties">
                        </div>

                        <p><strong>Tags</strong></p>
                        <div class="control-box" id="place_tags">
                        </div>

                        @include('partials/place-picture-filepicker')

                        @include('partials/place-cover-filepicker')

                        <div class="control-box">
                            <p><strong>Setting map radius *</strong></p>
                            <input type="hidden" name="latitude" value="{{ $latitude }}" class="mapFields formData">
                            <input type="hidden" name="longitude" value="{{ $longitude }}" class="mapFields formData">
                            <input type="hidden" name="radius" value="{{ $radius }}" class="mapFields formData">
                            <div class="map-wrap">
                                <div class="leaflet-map" id="mapid"></div>
                                <div id="marker"></div>
                            </div>
                            <p id="mapradius">Radius: <span>unknown</span> km.</p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">GPS</span>
                                    <input name="gps_crd" value="{{ $latitude }}, {{ $longitude }}">
                                </label>
                            </p>
                            <p class="hint">Invalid GPS-format. Example: 49.4274121,27.0085986</p>
                        </div>

                        @if(auth()->user()->isAdvertiser() || auth()->user()->isChiefAdvertiser())
                            <p class="notice-account-deactivate">
                                <strong>Notice! Your account will be disapproved, and all offers will be deactivated.</strong>
                                After the positive remark verification by Admin or Agent, your account will be approved again.
                            </p>
                        @endif

                        <p class="clearfix"><input type="submit" class="btn-nau pull-right" value="Save"></p>

                    </form>

                </div>

            </div>
        </div>
    </div>

@stop

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/partials/form.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('js/leaflet/leaflet.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/leaflet/leaflet.js') }}"></script>
    <script src="{{ asset('js/leaflet/leaflet.nau.js') }}"></script>

    <script>

        /* offer category and sub-categories */

        let formSelectCategory = document.getElementById("place_category");
        let formBoxRetailType = document.getElementById("place_retailtype");
        let formBoxSpecialties = document.getElementById("place_specialties");
        let formBoxTags = document.getElementById("place_tags");
        let placeInformation, firstTime = true;
        let spetialitiesCache = {};

        formSelectCategory.addEventListener('change', function (){
            let wait = '<img src="{{ asset('img/loading.gif') }}" alt="wait...">';
            formBoxRetailType.innerHTML = wait;
            formBoxSpecialties.innerHTML = wait;
            formBoxTags.innerHTML = wait;
            let url = "{{ route('categories') }}" + '/' + this.value + '?with=retailTypes;retailTypes.specialities;tags';
            srvRequest(url, 'GET', 'json', function (response){
                console.log('All categories, types, spetialities, tags:');
                console.dir(response);
                createRetailType(response);
                createSpecialties(response);
                createTags(response);
                firstTime = false;
            });
        });

        let rqURL = '/places/{{ $id }}?with=category;retailTypes;specialities;tags';
        srvRequest(rqURL, 'GET', 'json', function(response){
            console.log('Place categories, types, spetialities, tags:');
            console.dir(response);
            placeInformation = response;
            placeInformation.retail_types.forEach(function(rt){
                spetialitiesCache[rt.id] = {};
            });
            placeInformation.specialities.forEach(function(sp){
                spetialitiesCache[sp.retail_type_id][sp.slug] = true;
            });
            console.dir(spetialitiesCache);
            srvRequest("{{ route('categories') }}", 'GET', 'json', function(response){
                let html = '', selected;
                response.data.forEach(function(category){
                    selected = '';
                    if (placeInformation.category.length && placeInformation.category[0].id === category.id) selected = 'selected';
                    html += `<option value="${category.id}" ${selected}>${category.name}</option>`;
                });
                formSelectCategory.innerHTML = html;
                formSelectCategory.dispatchEvent(new Event('change'));
            });
        });

        function createRetailType(response) {
            let html = '', checked;
            response.retail_types.forEach(function(e){
                checked = '';
                if (firstTime) checked = hasRetailType(e.id) ? 'checked' : '';
                html += `<p><label><input type="checkbox" name="retail_types[]" value="${e.id}" ${checked}> ${e.name}</label></p>`;
            });
            formBoxRetailType.innerHTML = html;
            formBoxRetailType.querySelectorAll('input').forEach(function(checkbox){
                checkbox.addEventListener('change', function(){
                    if (!spetialitiesCache[this.value]) spetialitiesCache[this.value] = {};
                    createSpecialties(response);
                });
            });
            function hasRetailType(id){
                let res = false;
                placeInformation.retail_types.forEach(function(rt){ if (id === rt.id) res = true; });
                return res;
            }
        }

        function createSpecialties(response) {
            let html = '';
            formBoxRetailType.querySelectorAll('input').forEach(function(checkbox){
                if (!checkbox.checked) return;
                let s = '';
                function reatailType(e){ return e.id === checkbox.value; }
                response.retail_types.find(reatailType).specialities.forEach(function(e){
                    if (e.retail_type_id === checkbox.value) {
                        let type = e.group ? 'radio' : 'checkbox';
                        let name = e.group ? `name="${uuid2id(e.retail_type_id)}_${e.group}"` : '';
                        let checked = spetialitiesCache[e.retail_type_id][e.slug] ? 'checked' : '';
                        s += `<p><label><input type="${type}" ${name} value="${e.slug}" ${checked}> ${e.name}</label></p>`;
                    }
                });
                if (s) {
                    html += '<div class="specialities-group" data-id="' + checkbox.value + '"><p class="sgroup-title">';
                    html += checkbox.parentElement.innerText + ':</p><div class="sgroup-content">' + s + '</div></div>';
                }
            });
            formBoxSpecialties.innerHTML = html ? html : 'Select Retail Type';
            function hasSpecialty(rt_id, slug){
                let res = false;
                placeInformation.specialities.forEach(function(spec){
                    if (rt_id === spec.retail_type_id && slug === spec.slug) res = true;
                });
                return res;
            }
        }

        function createTags(response){
            let html = '', checked;
            response.tags.forEach(function(tag){
                checked = '';
                if (firstTime) checked = hasTag(tag.slug) ? 'checked' : '';
                html += `<label><input type="checkbox" value="${tag.slug}" ${checked}> <span>${tag.name}</span></label>`;
            });
            formBoxTags.innerHTML = html ? '<p>Please, select tags:</p><p>' + html + '</p>' : '<p>There is no one tag.</p>';
            function hasTag(slug){
                let res = false;
                placeInformation.tags.forEach(function(tag){ if (slug === tag.slug) res = true; });
                return res;
            }
        }



        /* specialities accordion */
        $('#place_specialties').on('click', '.sgroup-title', function(){
            $(this).toggleClass('active').next().slideToggle();
        }).on('change', 'input', function(){
            let uuid = $(this).parents('.specialities-group').attr('data-id');
            if ($(this).is('[type="checkbox"]')) {
                if ($(this).prop('checked')) spetialitiesCache[uuid][$(this).val()] = true;
                else delete spetialitiesCache[uuid][$(this).val()];
            } else {
                $(`[name="${$(this).attr('name')}"]`).not(':checked').each(function(){
                    delete spetialitiesCache[uuid][$(this).val()];
                }).end().filter(':checked').each(function(){
                    spetialitiesCache[uuid][$(this).val()] = true;
                });
            }
        });




        /* map */

        mapInit({
            id: 'mapid',
            setPosition: {
                lat: $('[name="latitude"]').val(),
                lng: $('[name="longitude"]').val(),
                radius: $('[name="radius"]').val()
            },
            done: mapDone,
            move: mapMove
        });

        function mapDone(map){
            let values = mapValues(map);
            $('#mapradius').children('span').text(values.radius / 1000);
            gpsField(map, document.querySelector('[name="gps_crd"]'), mapMove);
        }

        function mapMove(map){
            let values = mapValues(map);
            $('#mapradius').children('span').text(values.radius / 1000);
            $latitude = $('[name="latitude"]');
            $longitude = $('[name="longitude"]');
            $latitude.val(values.lat);
            $longitude.val(values.lng);
            $('[name="radius"]').val(values.radius);
            $('[name="gps_crd"]').val($latitude.val() + ', ' + $longitude.val());
            $('#alat').text(values.lat);
            $('#alng').text(values.lng);
        }



        /* picture and cover */

        let $place_picture_box = $('#place_picture_box');
        let $place_cover_box = $('#place_cover_box');
        $place_picture_box.add($place_cover_box).find('img').on('error', function(){
            $(this).attr('src', "{{ asset('/img/image_placeholder.jpg') }}");
        });
        $place_picture_box.find('img').attr('src', "{{ $picture_url }}");
        $place_cover_box.find('img').attr('src', "{{ $cover_url }}");

        $place_picture_box.find('[type="file"]').on('change', function(){
            $(this).attr('data-changed', 'true');
            console.log('Picture changed');
        });
        $place_cover_box.find('[type="file"]').on('change', function(){
            $(this).attr('data-changed', 'true');
            console.log('Cover changed');
        });




        /* form submit */

        $("#createPlaceForm").on('submit', function(e){
            e.preventDefault();

            if (!formValidation()) return false;
            let notice = 'Your account will be disapproved.\nDo you want to continue?';
            if ($('.notice-account-deactivate').length && !confirm(notice)) return false;

            let formData = $('.formData').serializeArray();

            formData.push({
                "name": "_token",
                "value": $('[name="_token"]').val()
            });

            formBoxRetailType.querySelectorAll('input:checked').forEach(function(checkbox){
                formData.push({
                    "name": "retail_types[]",
                    "value": checkbox.value
                });
            });

            formBoxSpecialties.querySelectorAll('.specialities-group').forEach(function(group, i){
                formData.push({
                    "name": `specialities[${i}][retail_type_id]`,
                    "value": group.dataset.id
                });
                group.querySelectorAll('input:checked').forEach(function(input, j){
                    formData.push({
                        "name": `specialities[${i}][specs][${j}]`,
                        "value": input.value
                    });
                });
            });

            formBoxTags.querySelectorAll('input:checked').forEach(function(checkbox){
                formData.push({
                    "name": "tags[]",
                    "value": checkbox.value
                });
            });

            console.dir(formData);

            $.ajax({
                type: "PATCH",
                url: $('#createPlaceForm').attr('action'),
                headers: { 'Accept': 'application/json' },
                data: formData,
                success: function(data, textStatus, xhr){
                    if (201 === xhr.status){
                        sendImages();
                    } else {
                        alert("Something went wrong. Try again, please.");
                        console.log(xhr.status);
                    }
                },
                error: function (resp) {
                    alert("Something went wrong. Try again, please.");
                    console.log(resp.status);
                }
            });

        });

        function formValidation(){
            let res = true;
            let $place_retailtype = $('#place_retailtype');
            if ($place_retailtype.find('input:checked').length < 1) {
                $place_retailtype.addClass('invalid').find('input').eq(0).focus();
                res = false;
            }
            let $place_name = $('[name="name"]');
            if ($place_name.val().length < 3) {
                $place_name.focus().parents('.control-text').addClass('invalid');
                res = false;
            }
            return res;
        }

        function sendImages(){
            let n = { count: 0 };
            let isNewPicture = $place_picture_box.find('[type="file"]').attr('data-changed');
            let isNewCover = $place_cover_box.find('[type="file"]').attr('data-changed');
            if (isNewPicture) n.count++;
            if (isNewCover) n.count++;
            redirectPage(n);
            if (isNewPicture) sendImage(n, $place_picture_box, "{{ route('places.picture.store', [$id]) }}", redirectPage);
            if (isNewCover) sendImage(n, $place_cover_box, "{{ route('places.cover.store', [$id]) }}", redirectPage);
        }

        function redirectPage(n){
            if (n.count === 0) {
                window.location.replace("{{ route('profile') }}");
            }
        }

        function sendImage(n, $box, URI, callback){
            let formData = new FormData();
            formData.append('_token', $box.find('[name="_token"]').val());
            formData.append('picture', $box.find('[type="file"]').get(0).files[0]);
            for(let i of formData) { console.log(i); }
            $.ajax({
                url: URI,
                data: formData,
                processData: false,
                contentType: false,
                method: 'POST',
                success: function () {
                    console.log('SUCCESS:', URI);
                    n.count -= 1;
                    callback(n);
                },
                error: function () {
                    console.log('Error:', URI);
                }
            });
        }


    </script>
@endpush