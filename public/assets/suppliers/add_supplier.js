$(document).ready(function () {
    $("#add_bank_detailsBtn").click(function () {
        $("#bankdDetails").collapse('toggle');
        hideShowPlusBtn($(this));
    })

    $("#add_general_details_btn").click(function () {
        $("#generalDetails").collapse('toggle');
        hideShowPlusBtn($(this));

    })

    const hideShowPlusBtn = (btn) => {
        if (btn.hasClass('fa-plus-circle')) {
            btn.removeClass('fa-plus-circle').addClass('fa-minus-circle');
        } else {
            btn.addClass('fa-plus-circle').removeClass('fa-minus-circle');

        }
    }

    $("#verifyBtn").click(function (e) {
        e.preventDefault();
        const gst_no = $("#gstin_number").val();
        // getAddress(gst_no);
        $(this).text('...')
        getPanInfo(gst_no);
        getCompnayDetails(gst_no,$(this));

    });


    const settings = (gst_no, end_point) => {
        return {
            async: true,
            crossDomain: true,
            url: `https://powerful-gstin-tool.p.rapidapi.com/v1/gstin/${gst_no}/${end_point}`,
            method: 'GET',
            headers: {
                'X-RapidAPI-Key': 'de544e2469mshb48b38540706c44p1a6154jsn8a1ea60b8e74',
                'X-RapidAPI-Host': 'powerful-gstin-tool.p.rapidapi.com'
            }
        };
    }

    const getAddress = (gst_no) => {
        $.ajax(settings(gst_no, "address")).done(function (res) {
            const address = `${res.data.place_of_business_principal.address.street}, ${res.data.place_of_business_principal.address.location}`;
            const pinCode = res.data.place_of_business_principal.address.pin_code;
            const city = res.data.place_of_business_principal.address.district;
            const state = res.data.place_of_business_principal.address.state
            $("#address").val(address);
            $("#city").val(city);
            $("#pin_code").val(pinCode);
            console.log("STate:", state)
            const stateCode = getStateCode(state);
            $('#state').val(stateCode).trigger('change');

        });
    }

    const getPanInfo=(gst_no)=>{
        $.ajax(settings(gst_no, "pan-info")).done(function (res) {
            console.log(res);
            const pan_number = res.data.pan_num;
            $("#pan_number").val(pan_number)
        }).error(function(){
          self.text('Verify');

        })
    }

    const getCompnayDetails=(gst_no,self)=>{
        $.ajax(settings(gst_no, "details")).done(function (res) {
            console.log(res);

            const address = `${res.data.place_of_business_principal.address.street}, ${res.data.place_of_business_principal.address.location}`;
            const pinCode = res.data.place_of_business_principal.address.pin_code;
            const city = res.data.place_of_business_principal.address.district;
            const state = res.data.place_of_business_principal.address.state
            const company_name = res.data.trade_name;
            $("#company_name").val(company_name);
            $("#address").val(address);
            $("#city").val(city);
            $("#pin_code").val(pinCode);
            $("#country_name").val('India');
           
            const stateCode = getStateCode(state);
            $('#state').val(stateCode).trigger('change');
          self.text('Verify');
        }).error(function(err){
            console.log(err.responseJSON.error);
          self.text('Verify');

            // errorNotifcation();
        })
    }

    const getStateCode = (stateName) => {
        let stateCode = '';
        switch (stateName) {
            case 'Andhra Pradesh':
                stateCode = 'AP';
                break;
            case 'Arunachal Pradesh':
                stateCode = 'AR';
                break;
            case 'Assam':
                stateCode = 'AS';
                break;
            case 'Bihar':
                stateCode = 'BR';
                break;
            case 'Chhattisgarh':
                stateCode = 'CT';
                break;
            case 'Goa':
                stateCode = 'GA';
                break;
            case 'Gujarat':
                stateCode = 'GJ';
                break;
            case 'Haryana':
                stateCode = 'HR';
                break;
            case 'Himachal Pradesh':
                stateCode = 'HP';
                break;
            case 'Jammu and Kashmir':
                stateCode = 'JK';
                break;
            case 'Jharkhand':
                stateCode = 'JH';
                break;
            case 'Karnataka':
                stateCode = 'KA';
                break;
            case 'Kerala':
                stateCode = 'KL';
                break;
            case 'Madhya Pradesh':
                stateCode = 'MP';
                break;
            case 'Maharashtra':
                stateCode = 'MH';
                break;
            case 'Manipur':
                stateCode = 'MN';
                break;
            case 'Meghalaya':
                stateCode = 'ML';
                break;
            case 'Mizoram':
                stateCode = 'MZ';
                break;
            case 'Nagaland':
                stateCode = 'NL';
                break;
            case 'Odisha':
                stateCode = 'OR';
                break;
            case 'Punjab':
                stateCode = 'PB';
                break;
            case 'Rajasthan':
                stateCode = 'RJ';
                break;
            case 'Sikkim':
                stateCode = 'SK';
                break;
            case 'Tamil Nadu':
                stateCode = 'TN';
                break;
            case 'Telangana':
                stateCode = 'TG';
                break;
            case 'Tripura':
                stateCode = 'TR';
                break;
            case 'Uttar Pradesh':
                stateCode = 'UP';
                break;
            case 'Uttarakhand':
                stateCode = 'UT';
                break;
            case 'West Bengal':
                stateCode = 'WB';
                break;
            case 'Andaman and Nicobar Islands':
                stateCode = 'AN';
                break;
            case 'Chandigarh':
                stateCode = 'CH';
                break;
            case 'Dadra and Nagar Haveli':
                stateCode = 'DN';
                break;
            case 'Daman and Diu':
                stateCode = 'DD';
                break;
            case 'Lakshadweep':
                stateCode = 'LD';
                break;
            case 'National Capital Territory of Delhi':
                stateCode = 'DL';
                break;
            case 'Puducherry':
                stateCode = 'PY';
                break;


        }
        return stateCode;

    }
})