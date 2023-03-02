<div class="b-widget-page">
    <div class="b-page-header text-center mb-0 js-full-width">
        <h1 class="b-page-header__title">{{ $page->title }}</h1>
        <div class="b-page-header__desc">{!! $page->subtitle !!}</div>
    </div>

    <div class="b-widget-page__form b-hashrate-convert">

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <div onclick="loadUnit('H', this);" class="nav-link active">Hashes</div>
            </li>
            <li class="nav-item">
                <div onclick="loadUnit('Sol', this);" class="nav-link">Sols</div>
            </li>
            <li class="nav-item">
                <div onclick="loadUnit('G', this);" class="nav-link">Graphs</div>
            </li>
        </ul>
        <div class="mb-3">
            <div class="input-group">
                <input autocomplete="off" id="input-hash" name="hash" class="form-control" type="text"
                    value="1000000">
                <label id="hash-unit" for="input-hash" class="input-group-prepend">
                    <div class="input-group-text">H/s</div>
                </label>
            </div>
        </div>
        <div class="mb-3">
            <div class="input-group">
                <input autocomplete="off" id="input-kilohash" name="kilohash" class="form-control" type="text"
                    value="1000">
                <label id="kilohash-unit" for="input-kilohash" class="input-group-prepend">
                    <div class="input-group-text">kH/s</div>
                </label>
            </div>
        </div>
        <div class="mb-3">
            <div class="input-group">
                <input autocomplete="off" id="input-megahash" name="megahash" class="form-control" type="text"
                    value="1">
                <label id="megahash-unit" for="input-megahash" class="input-group-prepend">
                    <div class="input-group-text">MH/s</div>
                </label>
            </div>
        </div>
        <div class="mb-3">
            <div class="input-group">
                <input autocomplete="off" id="input-gigahash" name="gigahash" class="form-control" type="text"
                    value="0.001">
                <label id="gigahash-unit" for="input-gigahash" class="input-group-prepend">
                    <div class="input-group-text">GH/s</div>
                </label>
            </div>
        </div>
        <div class="mb-3">
            <div class="input-group">
                <input autocomplete="off" id="input-terahash" name="terahash" class="form-control" type="text"
                    value="0.000001">
                <label id="terahash-unit" for="input-terahash" class="input-group-prepend">
                    <div class="input-group-text">TH/s</div>
                </label>
            </div>
        </div>
        <div class="mb-3">
            <div class="input-group">
                <input autocomplete="off" id="input-petahash" name="petahash" class="form-control" type="text"
                    value="0.000000001">
                <label id="petahash-unit" for="input-petahash" class="input-group-prepend">
                    <div class="input-group-text">PH/s</div>
                </label>
            </div>
        </div>
        <div class="input-group">
            <input autocomplete="off" id="input-exahash" name="exahash" class="form-control" type="text"
                value="0.000000000001">
            <label id="exahash-unit" for="input-exahash" class="input-group-prepend">
                <div class="input-group-text">EH/s</div>
            </label>
        </div>

    </div>
</div>

@component('components.page_description')
    @slot('description', $page->description)
@endcomponent

@section('script')
    @parent

    <script>
        let hashvalue, kilohashvalue, megahashvalue, gigahashvalue, terahashvalue, petahashvalue, exahashvalue;

        document.querySelectorAll("input").forEach(item => {
            item.addEventListener("keyup", function () {
                let value = this.value,
                    type = this.getAttribute("name");

                if (value != ""
                    && !(value[value.length - 1] == "." || value[value.length - 1] == ",")
                    && parseFloat(value) != 0
                ) {
                    value = value.replace(",", ".");
                    value = parseFloat(value);

                    if (type == "hash") {
                        hashValue     = correctValue(value);
                        kilohashvalue = correctValue(value / 1000);
                        megahashvalue = correctValue(value / 1000000);
                        gigahashvalue = correctValue(value / 1000000000);
                        terahashvalue = correctValue(value / 1000000000000);
                        petahashvalue = correctValue(value / 1000000000000000);
                        exahashvalue  = correctValue(value / 1000000000000000000);
                    } else if (type == "kilohash") {
                        hashValue     = correctValue(value * 1000);
                        kilohashvalue = correctValue(value);
                        megahashvalue = correctValue(value / 1000);
                        gigahashvalue = correctValue(value / 1000000);
                        terahashvalue = correctValue(value / 1000000000);
                        petahashvalue = correctValue(value / 1000000000000);
                        exahashvalue  = correctValue(value / 1000000000000000);
                    } else if (type == "megahash") {
                        hashValue     = correctValue(value * 1000000);
                        kilohashvalue = correctValue(value * 1000);
                        megahashvalue = correctValue(value);
                        gigahashvalue = correctValue(value / 1000);
                        terahashvalue = correctValue(value / 1000000);
                        petahashvalue = correctValue(value / 1000000000);
                        exahashvalue  = correctValue(value / 1000000000000);
                    } else if (type == "gigahash") {
                        hashValue     = correctValue(value * 1000000000);
                        kilohashvalue = correctValue(value * 1000000);
                        megahashvalue = correctValue(value * 1000);
                        gigahashvalue = correctValue(value);
                        terahashvalue = correctValue(value / 1000);
                        petahashvalue = correctValue(value / 1000000);
                        exahashvalue  = correctValue(value / 1000000000);
                    } else if (type == "terahash") {
                        hashValue     = correctValue(value * 1000000000000);
                        kilohashvalue = correctValue(value * 1000000000);
                        megahashvalue = correctValue(value * 1000000);
                        gigahashvalue = correctValue(value * 1000);
                        terahashvalue = correctValue(value);
                        petahashvalue = correctValue(value / 1000);
                        exahashvalue  = correctValue(value / 1000000);
                    } else if (type == "petahash") {
                        hashValue     = correctValue(value * 1000000000000000);
                        kilohashvalue = correctValue(value * 1000000000000);
                        megahashvalue = correctValue(value * 1000000000);
                        gigahashvalue = correctValue(value * 1000000);
                        terahashvalue = correctValue(value * 1000);
                        petahashvalue = correctValue(value);
                        exahashvalue  = correctValue(value / 1000);
                    } else if (type == "exahash") {
                        hashValue     = correctValue(value * 1000000000000000000);
                        kilohashvalue = correctValue(value * 1000000000000000);
                        megahashvalue = correctValue(value * 1000000000000);
                        gigahashvalue = correctValue(value * 1000000000);
                        terahashvalue = correctValue(value * 1000000);
                        petahashvalue = correctValue(value * 1000);
                        exahashvalue  = correctValue(value);
                    }

                    document.querySelector("#input-hash").value = hashValue;
                    document.querySelector("#input-kilohash").value = kilohashvalue;
                    document.querySelector("#input-megahash").value = megahashvalue;
                    document.querySelector("#input-gigahash").value = gigahashvalue;
                    document.querySelector("#input-terahash").value = terahashvalue;
                    document.querySelector("#input-petahash").value = petahashvalue;
                    document.querySelector("#input-exahash").value = exahashvalue;
                }
            });
        });

        function correctValue(number) {
            let roundedNumber;

            roundedNumber = Math.round(1000 * number) / 1000;
            if (roundedNumber > 0) return roundedNumber;

            roundedNumber = Math.round(10000 * number) / 10000;
            if (roundedNumber > 0) return roundedNumber;

            roundedNumber = Math.round(100000 * number) / 100000;
            if (roundedNumber > 0) return roundedNumber;

            roundedNumber = Math.round(1000000 * number) / 1000000;
            if (roundedNumber > 0) return roundedNumber;

            let numberArray = String(number).split("e-");

            if (numberArray.length > 1) {
                let toFixedNum = parseInt(numberArray[1]) + 3;
                return number.toFixed(toFixedNum);
            } else {
                return number;
            }
        }

        function loadUnit(unit, el) {
            document.querySelectorAll(".nav-tabs .nav-link").forEach(item => {
                item.classList.remove("active");
            });
            el.classList.add("active");

            document.querySelector("#hash-unit div").innerHTML = unit + "/s";
            document.querySelector("#kilohash-unit div").innerHTML = "k" + unit + "/s";
            document.querySelector("#megahash-unit div").innerHTML = "M" + unit + "/s";
            document.querySelector("#gigahash-unit div").innerHTML = "G" + unit + "/s";
            document.querySelector("#terahash-unit div").innerHTML = "T" + unit + "/s";
            document.querySelector("#petahash-unit div").innerHTML = "P" + unit + "/s";
            document.querySelector("#exahash-unit div").innerHTML = "E" + unit + "/s";
        }
	</script>
@endsection
