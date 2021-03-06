<div id="working_area" style="position: absolute; opacity: 0; z-index: -1;">
    <p class="title" style="padding-top: 20px;">Working area</p>

    <div class="control-box">
        <p class="control-text">
            <label>
                <span class="input-label">Country</span>
                <input name="country" value="{{ $country ?: '' }}" class="nullableFormData">
            </label>
        </p>
    </div>

    <div class="control-box">
        <p class="control-text">
            <label>
                <span class="input-label">City</span>
                <input name="city" value="{{ $city ?: '' }}" class="nullableFormData">
            </label>
        </p>
    </div>

    <div class="control-box" id="map_box">
        <p>To set Offer location, you can use the map or input GPS coordinates or address in the appropriate field.</p>
        <p><strong>Setting map radius</strong></p>

        <div class="map-wrap" >
            <div class="leaflet-map" id="mapid">
            </div>
            <div id="marker"></div>
        </div>
        <p id="mapradius">Radius: <span>unknown</span> km.</p>

        <input type="hidden" name="latitude" value="{{ $latitude }}" class="nullableFormData">
        <input type="hidden" name="longitude" value="{{ $longitude }}" class="nullableFormData">
        <input type="hidden" name="radius" value="{{ $radius }}" class="nullableFormData">
        <input type="hidden" name="timezone" value="">
        <input type="hidden" name="timeframes_offset" value="{{ $timeframes_offset }}" class="formData">
        <input type="hidden" name="place_timezone_offset" value="{{ auth()->user()->place->timezone_offset }}">
    </div>

    <p class="hint">You can not choose sea or ocean.</p>

    <div class="control-box">
        <div class="row gps-crd-box">
            <div class="col-xs-10">
                <p class="control-text">
                    <label>
                        <span class="input-label">Address or GPS</span>
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
            &nbsp;&nbsp;&nbsp;&nbsp;<em>Львів, Кобиляньської 16</em><br><br>
            Example of GPS coordinates:<br>
            &nbsp;&nbsp;&nbsp;&nbsp;<em>49.4213687,26.9971402</em>
        </p>
    </div>
</div>