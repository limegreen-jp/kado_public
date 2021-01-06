class Price {
    constructor() {
        this.skill_id;
        this.level_id;
        this.price;
        this.month_price;
        this.month_unit_price;
        this.hour_unit_price;
        this.working_time;
        this.month_working_time;
    }

    set skillId(skill_id) {
        this.skill_id = skill_id;
    }
    get skillId() {
        return this.skill_id;
    }

    set levelId(level_id) {
        this.level_id = level_id;
    }
    get levelId() {
        return this.level_id;
    }
    
    get monthUnitPrice() {
        return this.month_unit_price;
    }
    
    get hourUnitPrice() {
        return this.hour_unit_price;
    }

    set workingTime(working_time) {
        this.working_time = working_time;
    }
    get workingTime() {
        return this.working_time;
    }

    set monthWorkingTime(month_working_time) {
        this.month_working_time = month_working_time;
    }
    get monthWorkingTime() {
        return this.month_working_time;
    }

    abledLevelSelect() {
        if (this.isset($('#skill_id').val())) {
            $('#level_id').prop('disabled', false);
        }
    }

    disabledLevelOption(data, skill_id) {
        let levels = data[skill_id];

        $('#level_id option').prop('disabled', true);
        for (let key in levels) {
            $('#level_id option[value="'+key+'"]').prop('disabled', false);
        }
    }

    calcMonthUnitPrice(data) {
        if (this.isset(this.skill_id) && this.isset(this.level_id)) {
            this.month_unit_price = data[this.skill_id][this.level_id];
        }
    }

    setMonthUnitPriceText(text) {
        if (this.isset(this.month_unit_price)) {
            $('#month_unit_price').text(text+'万/月');
        } else {
            $('#month_unit_price').text('');
        }
    }

    calcHourUnitPrice() {
        if (this.isset(this.month_unit_price)) {
            this.hour_unit_price = (this.month_unit_price * 10000) / 160;
        }
    }

    setHourUnitPriceText(text) {
        if (this.isset(this.hour_unit_price)) {
            $('#hour_unit_price').text(text+'円/h');
        } else {
            $('#hour_unit_price').text('');
        }
    }

    calcPrice() {
        if (this.isset(this.hour_unit_price) && this.isset(this.working_time)) {
            this.price = this.hour_unit_price * this.working_time / 10000;
        }
    }

    calcMonthPrice() {
        if (this.isset(this.hour_unit_price) && this.isset(this.month_working_time)) {
            this.month_price = this.hour_unit_price * this.month_working_time / 10000;
        }
    }

    setPriceText(text) {
        if (this.isset(this.price)) {
            $('#price').text(text+'万');
        } else {
            $('#price').text('');
        }
    }

    setPriceValue(price) {
        if (this.isset(this.price)) {
            $('#price_value').val(price);
        } else {
            $('#price_value').val(null);
        }
    }

    setMonthPriceText(data_date, text) {
        if (this.isset(this.month_price)) {
            $('.table-price-text').each(function() {
                if ($(this).attr('data-date-text') == data_date) {
                    text = (Math.round(text * 10)) / 10;
                    $(this).text(text+'万');
                }
            });
        } else {
            $('.table-price-text').each(function() {
                if ($(this).attr('data-date-text') == data_date) {
                    $(this).text('');
                }
            });
        }
    }

    setMonthPriceValue(data_date, price) {
        if (this.isset(this.month_price)) {
            $('.table-price-value').each(function() {
                if ($(this).attr('data-date-value') == data_date) {
                    price = (Math.round(price * 10)) / 10;
                    $(this).val(price);
                }
            });
        } else {
            $('.table-price-value').each(function() {
                if ($(this).attr('data-date-value') == data_date) {
                    $(this).val(null);
                }
            });
        }
    }

    monthUnitPriceFuns(data) {
        this.calcMonthUnitPrice(data);
        this.setMonthUnitPriceText(this.month_unit_price);
    }

    hourUnitPriceFuns() {
        this.calcHourUnitPrice();
        this.setHourUnitPriceText(this.hour_unit_price);
    }

    priceFuns() {
        this.calcPrice();
        this.setPriceText(this.price);
        this.setPriceValue(this.price);
    }

    monthPriceFuns(data) {
        this.calcMonthPrice();
        this.setMonthPriceText(data, this.month_price);
        this.setMonthPriceValue(data, this.month_price);
    }

    monthPriceFunsEach() {
        $('.working_time_month').each(function(index, element) {
            if (this.isset($(element).val())) {
                this.month_working_time = $(element).val();
                this.monthPriceFuns($(element).attr('data-date'));
            }
        }.bind(this));
    }

    isset(data) {
        if(data === "" || data === null || data === undefined){
            return false;
        }else{
            return true;
        }
    }
}