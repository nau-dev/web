@extends('layouts.master')

@section('title', 'Edit advertiser place')

@section('content')

    <div class="container">
        <div class="row">

            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">

                <div>
                    <form action="{{ route('places.update', $id) }}" method="POST" class="nau-form" id="createPlaceForm" target="_top">
                        {{ csrf_field() }}
                        <p class="title" style="margin-top: 32px;">Edit advertiser place</p>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">Name *</span>
                                    <input name="name" value="{{ $name }}" class="formData" data-max-length="30">
                                </label>
                            </p>
                            <p class="hint">Please, enter the Place name (minimum 3 characters).</p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">Description</span>
                                    <textarea name="description" class="formData" data-max-length="120">{{ $description }}</textarea>
                                </label>
                            </p>
                            <p class="hint">Please, enter the Place description.</p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">About</span>
                                    <textarea name="about" class="formData" data-max-length="1024">{{ $about }}</textarea>
                                </label>
                            </p>
                            <p class="hint">Please, enter the information About Place.</p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">Alias *</span>
                                    <input name="alias" value="{{ $alias }}" class="formData">
                                </label>
                            </p>
                            <p class="hint">
                                Please, enter the Place Alias.<br>
                                The alias must be at least 3 characters.
                            </p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">Phone</span>
                                    <input name="phone" value="{{ $phone }}" class="formData" maxlength="16">
                                </label>
                            </p>
                            <p class="hint">Please, enter the Place phone, example: <em>+1234567890</em>, 10-15 digits.</p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">Web-site</span>
                                    <input name="site" value="{{ $site }}" class="formData" maxlength="64">
                                </label>
                            </p>
                            <p class="hint">Please, enter the Place site, example: <em>http://mysite.com</em>.</p>
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

                        <div class="control-box" id="map_box">
                            <p><strong>Setting map radius *</strong></p>
                            <input type="hidden" name="latitude" value="{{ $latitude }}" class="formData">
                            <input type="hidden" name="longitude" value="{{ $longitude }}" class="formData">
                            <input type="hidden" name="radius" value="{{ $radius }}" class="formData">
                            <input type="hidden" name="timezone" value="{{ $timezone ?: '' }}" class="formData">
                            <input type="hidden" name="timezone_offset" value="{{ $timezone_offset ?: '' }}" class="formData">
                            <div class="map-wrap">
                                <div class="leaflet-map" id="mapid"></div>
                                <div id="marker"></div>
                            </div>
                            <p id="mapradius">Radius: <span>unknown</span> km.</p>
                        </div>

                        <div class="control-box">
                            <div class="row gps-crd-box">
                                <div class="col-xs-10">
                                    <p class="control-text">
                                        <label>
                                            <span class="input-label">Search point by address or GPS</span>
                                            <input name="gps_crd" value="">
                                        </label>
                                    </p>
                                </div>
                                <div class="col-xs-2">
                                    &nbsp;<br>
                                    <span class="btn" id="btn_gps_crd">Go</span>
                                </div>
                            </div>
                            <p class="hint">Invalid address or GPS coordinates: object not found.</p>
                            <p class="address-examples">
                                Examples of address:<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;<em>6931 Atlantic LA CA</em><br>
                                &nbsp;&nbsp;&nbsp;&nbsp;<em>Australia, Melbourne, Peate Ave, 16</em><br>
                                &nbsp;&nbsp;&nbsp;&nbsp;<em>Львів, Кобиляньської 16</em><br>
                                Example of GPS coordinates:<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;<em>49.4213687,26.9971402</em>
                            </p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">Address</span>
                                    <input name="address" value="{{ $address }}" class="formData">
                                </label>
                            </p>
                            <p class="hint">Please, enter the Place address.</p>
                            <p class="address-examples">You can edit the address at your discretion.</p>
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
    <link rel="stylesheet" type="text/css" href="{{ asset('js/cropper/imageuploader.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('js/cropper/cropper.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/formdata.min.js') }}"></script>
    <script src="{{ asset('js/leaflet/leaflet.js') }}"></script>
    <script src="{{ asset('js/leaflet/leaflet.nau.js') }}"></script>
    <script src="{{ asset('js/cropper/imageuploader.js') }}"></script>
    <script src="{{ asset('js/cropper/cropper.js') }}"></script>
    <script>

        let redirectUrl;

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
            console.log('Place categories, types, specialities, tags:');
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

        /* you can not input more than N characters in this fields */
        setFieldLimit('[data-max-length]');

        /* fields validator */
        validationOnFly();

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

        /* picture and cover */
        imageUploader('#logo_image_box .image-box');
        imageUploader('#cover_image_box .image-box');
        let $logo_image_box = $('#logo_image_box');
        let $cover_image_box = $('#cover_image_box');
        $logo_image_box.find('[type="file"]').on('change', function(){
            $(this).attr('data-changed', 'true');
            console.log('Logo changed');
            $logo_image_box.find('.image').attr('data-cropratio', '1').attr('data-circle', 'true');
        });
        $logo_image_box.find('.image').attr('src', "{{ $picture_url }}?v={{ $updated_at }}").on('load', function(){
            $(this).parents('.img-hide').removeClass('img-hide');
            if (this.dataset.cropratio) {
                imageCropperRemove(this);
                imageCropperInit(this);
            }
        });
        $cover_image_box.find('[type="file"]').on('change', function(){
            $(this).attr('data-changed', 'true');
            console.log('Cover changed');
            $cover_image_box.find('.image').attr('data-cropratio', '3');
        });
        $cover_image_box.find('.image').attr('src', "{{ $cover_url }}?v={{ $updated_at }}").on('load', function(){
            $(this).parents('.img-hide').removeClass('img-hide');
            if (this.dataset.cropratio) {
                imageCropperRemove(this);
                imageCropperInit(this);
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

        function mapDone(map){
            let values = mapValues(map);
            $('#mapradius').children('span').text(values.radius / 1000);
            /* set map position by GPS or Address */
            addAction_setMapPositionByGpsOrAddress(map);
        }

        function mapMove(map){
            let values = mapValues(map);
            $('#mapradius').children('span').text(values.radius / 1000);
            let $latitude = $('[name="latitude"]');
            let $longitude = $('[name="longitude"]');
            $latitude.val(values.lat);
            $longitude.val(values.lng);
            $('[name="radius"]').val(values.radius);
            $('#alat').text(values.lat);
            $('#alng').text(values.lng);
            $('[name="address"]').val('');
            setAddresByGps();
            getTimeZoneMap(map, function(tz, tzXHR){
                $('[name="timezone"]').val(tzXHR.timeZoneId);
                $('[name="timezone_offset"]').val(tzXHR.rawOffset);
            });
        }

        /* form submit */

        $('#createPlaceForm').on('submit', function(e){
            e.preventDefault();

            if (!formValidation()) return false;
            let notice = 'Your account will be disapproved.\nDo you want to continue?';
            if ($('.notice-account-deactivate').length && !confirm(notice)) return false;

            let formData = $('.formData').serializeArray();

            formData.push({
                "name": "_token",
                "value": $('[name="_token"]').val().toString()
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

            waitPopup(false);

            $.ajax({
                type: "PATCH",
                url: $('#createPlaceForm').attr('action'),
                headers: { 'Accept': 'application/json' },
                data: formData,
                success: function(data, textStatus, xhr){
                    if (201 === xhr.status){
                        redirectUrl = xhr.getResponseHeader('Location');
                        sendImages();
                    } else {
                        $('#waitError').text('Status: ' + xhr.status);
                        console.log("Something went wrong. Try again, please.");
                        console.log(xhr.status);
                    }
                },
                error: function (resp) {
                    if (401 === resp.status) UnAuthorized();
                    else if (0 === resp.status) AdBlockNotification();
                    else if (422 === resp.status) {
                        let msg = '';
                        let obj = JSON.parse(resp.responseText);
                        for (let key in obj) {
                            if (msg.length) msg += '\n';
                            msg += obj[key][0];
                        }
                        alert(msg);
                        $('#waitPopupOverlay').remove();
                        $('[name="alias"]').focus();
                    } else {
                        $('#waitError').text(`Error ${resp.status}: ${resp.responseText}`);
                        console.log("Something went wrong. Try again, please.");
                        console.log(resp.status);
                    }
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
            let $place_site = $('[name="site"]');
            if ($place_site.parents('p').hasClass('invalid')) {
                $place_site.focus();
                res = false;
            }
            let $place_phone = $('[name="phone"]');
            if ($place_phone.parents('p').hasClass('invalid')) {
                $place_phone.focus();
                res = false;
            }
            let $place_alias = $('[name="alias"]');
            if ($place_alias.val().length < 3) {
                $place_alias.focus().parents('p').addClass('invalid');
                res = false;
            } else $place_alias.parents('p').removeClass('invalid');
            let $place_name = $('[name="name"]');
            if ($place_name.val().length < 3) {
                $place_name.focus().parents('.control-text').addClass('invalid');
                res = false;
            }
            if ($('[name="timezone"]').val() === 'error') {
                alert('Timezone error');
                $('html, body').animate({ scrollTop: $('#map_box').offset().top }, 400);
                res = false;
            }
            return res;
        }

        function sendImages(){
            let n = { count: 0 };
            let $logo = $logo_image_box.find('[type="file"]');
            let $cover = $cover_image_box.find('[type="file"]');
            let isNewLogo = $logo.attr('data-changed') && $logo.val();
            let isNewCover = $cover.attr('data-changed') && $cover.val();
            if (isNewLogo) n.count++;
            if (isNewCover) n.count++;
            redirectPage(n);
            if (isNewLogo) sendImage(n, $logo_image_box, "{{ route('places.picture.store', [$id]) }}", redirectPage);
            if (isNewCover) sendImage(n, $cover_image_box, "{{ route('places.cover.store', [$id]) }}", redirectPage);
        }

        function redirectPage(n){
            if (n.count === 0) window.location.replace(redirectUrl);
        }

        function sendImage(n, $box, URI, callback){
            let formData = new FormData();
            formData.append('_token', $('[name="_token"]').val().toString());
            /*if ($box.attr('id') === 'logo_image_box') {
                formData.append('picture', $box.find('[type="file"]').get(0).files[0]);
            } else {
                let base64Data = imageCropperCrop($box.find('.image').get(0)).getAttribute('src').replace(/^data:image\/(png|jpg|jpeg);base64,/, "");
                formData.append('picture', base64toBlob(base64Data, 'image/jpeg'), 'cover.jpg');
            }*/
            let imgName = $box.attr('id') === 'logo_image_box' ? 'logo' : 'cover';
            let base64Data = imageCropperCrop($box.find('.image').get(0)).getAttribute('src').replace(/^data:image\/(png|jpg|jpeg);base64,/, "");
            formData.append('picture', base64toBlob(base64Data, 'image/jpeg'), imgName + '.jpg');
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
                error: function (resp) {
                    if (401 === resp.status) UnAuthorized();
                    else if (0 === resp.status) AdBlockNotification();
                    else {
                        $('#waitError').text(resp.status);
                        console.log('Error:', URI);
                    }
                }
            });
        }

        function addAction_setMapPositionByGpsOrAddress(map){
            let $gps_crd = $('[name="gps_crd"]');
            $('#btn_gps_crd').on('click', btnSearchPointClick);
            $gps_crd.on('keypress', function(e){
                if (e.keyCode === 13) { btnSearchPointClick(); return false; }
            });
            function btnSearchPointClick(){
                let address = $gps_crd.val().trim();
                if (address.length < 5) return false;
                address = tryConvertToGPS(address);
                if (address.lat) {
                    map.panTo(address);
                    mapMove(map);
                } else {
                    getGpsByAddress(address, function(response){
                        if (response.results.length) {
                            map.panTo(response.results[0].geometry.location);
                            mapMove(map);
                        } else {
                            $gps_crd.parents('.gps-crd-box').addClass('invalid');
                        }
                    });
                }
            }
            function tryConvertToGPS(str){
                let arr = str.split(/,\s*/);
                if (arr.length !== 2) return str;
                let lat = parseFloat(arr[0]);
                let lng = parseFloat(arr[1]);
                if (isNaN(lat) || isNaN(lng)) return str;
                return {lat, lng};
            }
        }

        function validationOnFly(){
            /* phone validator */
            document.getElementsByName('phone')[0].addEventListener('input', function(){
                let p = this.parentElement.parentElement;
                p.classList.remove('invalid');
                let val = this.value.trim();
                val = val.replace(/[^0-9+]/, '');
                if (val.length && val[0] !== '+') val = '+' + val;
                this.value = val;
                if (val.length && !/^\+[0-9]{10,15}$/.test(val)) p.classList.add('invalid');
            });
            /* website validator */
            document.getElementsByName('site')[0].addEventListener('input', function(){
                let p = this.parentElement.parentElement;
                p.classList.remove('invalid');
                let val = this.value.trim();
                this.value = val;
                if (val.length && !/^https?:\/\/.+\..{2,}$/.test(val)) p.classList.add('invalid');
            });
        }

        function setAddresByGps(){
            let addressField = document.getElementsByName('address')[0];
            let lat = parseFloat(document.getElementsByName('latitude')[0].value);
            let lng = parseFloat(document.getElementsByName('longitude')[0].value);
            if (addressField.value.length > 0 || isNaN(lat) || isNaN(lng)) return;
            getAddressByGps(lat, lng, function(xhr){
                if (xhr && xhr.status === 'OK' && xhr.results && xhr.results.length > 0 && xhr.results[0].formatted_address) {
                    let address = xhr.results[0].formatted_address;
                    console.log('Found address:', address);
                    addressField.value = address;
                }
            });
        }

    </script>
@endpush
