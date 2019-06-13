var myForm;
var myCombo;
var listType = scheduler.serverList("type");

var input = document.getElementsByName("contact_name");
var prev =  document.getElementsByClassName('dhxcombo_input');

var user_id;
var dp;
var scheduler;
function init() {

    // CONFIGURATION
    scheduler.config.xml_date="%Y-%m-%d %H:%i";
    scheduler.config.time_step = 30;
    scheduler.config.first_hour = 6;
    //
    scheduler.config.prevent_cache = true;
    scheduler.config.occurrence_timestamp_in_utc = true;
    scheduler.config.include_end_by = true;
    scheduler.config.repeat_precise = true;
    scheduler.config.multi_day = true;
    scheduler.config.limit_time_select = true;
    scheduler.config.className = 'dhtmlXTooltip tooltip';
    scheduler.config.timeout_to_display = 5;
    scheduler.config.delta_x = 15;
    scheduler.config.delta_y = -20;

    scheduler.templates.hour_scale = function(date){
        var t=date.getMinutes();t=10>t?"0"+t:t;
        var i="<span class='dhx_scale_h'>"+date.getHours()+"</span><span class='dhx_scale_m'>&nbsp;"+t+"</span>";
        return i;
    };
    scheduler.templates.tooltip_text = function(start,end,ev){
        if(listType[ev.type] != undefined){
            return  "<b>Détails:</b> "+ev.details+"<br/>" +
                "<b>Type:</b> "+listType[ev.type].label +"<br/>" +
                "<b>Start date:</b> "+scheduler.templates.tooltip_date_format(start)+
                "<br/><b>End date:</b> "+scheduler.templates.tooltip_date_format(end);
        }
    };
    scheduler.templates.event_text=scheduler.templates.event_bar_text = function(start,end,event){
        if(listType[event.type] != undefined) {
            return listType[event.type].label + ' ' + event.text  ;
        }
    };

    // CUSTOM COLOR EVENT TYPE
    scheduler.templates.event_class=function(start, end, event){
        var css = "";
        if(event.type) // if event has subject property then special class should be assigned
            css += "event_"+event.type;

        if(event.id == scheduler.getState().select_id){
            css += " selected";
        }
        return css; // default return
    };

    scheduler.form_blocks["my_editor"] = {
        render:function(sns) {
            var html =
                "<div class='dhx_cal_ltext row' style='' id='customY'>" +
                "<div class='col-md-12'>Identité</div>" +
                "<span class='hidden'>Code contact&nbsp;<input type='hidden'><br/></span>"+
                "<span class='hidden'>Code user&nbsp;<input type='hidden'><br/></span>"+
                "<div class='col-md-12'><input name='form_code_contact' class='form-control'  id='form_code_contact'></input></div>" +
                "<div class='col-md-2'><input type='text' class='form-control ' name='form_contact_civ'   id='form_contact_civ'  placeholder='Civilité'></div>" +
                "<div class='col-md-5'><input type='text' class='form-control ' name='form_contact_nom'   id='form_contact_nom'  placeholder='Nom'></div>" +
                "<div class='col-md-5'><input type='text' class='form-control ' name='form_contact_prenom'   id='form_contact_prenom'  placeholder='Prénom'></div>" +
                "<div class='col-md-12'>Adresse</div>" +
                "<div class='col-md-6'><input type='text' class='form-control ' name='form_contact_adr1'   id='form_contact_adr1'  placeholder='Adresse1'></div>" +
                "<div class='col-md-6'><input type='text' class='form-control ' name='form_contact_cp'   id='form_contact_cp'  placeholder='CP'></div>" +
                "<div class='col-md-6'><input type='text' class='form-control ' name='form_contact_adr2'   id='form_contact_adr2'  placeholder='Adresse2'></div>" +
                "<div class='col-md-6'><input type='text' class='form-control ' name='form_contact_ville'   id='form_contact_ville'  placeholder='Ville'></div>" +
                "<div class='col-md-6'><input type='text' class='form-control ' name='form_contact_adr3'   id='form_contact_adr3'  placeholder='Adresse3'></div>" +
                "<div class='col-md-12'>Coordonnées</div>" +
                "<div class='col-md-12'><input type='text' class='form-control ' name='form_contact_mail'   id='form_contact_mail'  placeholder='Email'></div>" +
                "<div class='col-md-4'><input type='text' class='form-control '  name='form_contact_tel1'   id='form_contact_tel1'  placeholder='Tel1'></div>" +
                "<div class='col-md-4'><input type='text' class='form-control '  name='form_contact_tel2'   id='form_contact_tel2'  placeholder='Tel2'></div>" +
                "<div class='col-md-4'><input type='text' class='form-control '  name='form_contact_tel3'   id='form_contact_tel3'  placeholder='Tel3'></div>" +
                "</div>";
            return html;
        },
        // TAF PRESENTATION QUAND ON VEUT VISUALISER LE CONTACT
        set_value:function(node, value, ev) {
            var contact_id;
            var params;
            console.log(ev);
            if(ev.userId != undefined) {
                console.log('userId= '+ev.userId)
                user_id = ev.userId;
            }

            /**
             * Si un event est deja rattaché a un contact defini l'id du contact comme etant celui de la table event
             * Sinon on prend la valeur de l'id selectionner dans la liste deroulante contact
             */
            if (value != undefined) {
                contact_id = value;
                params = 'contact_id=' + value;
            } else {
                contact_id = document.getElementsByName('contact_name')[0].value;
                params = 'contact_id=' + contact_id;
            }

            if (contact_id != "") {
                var request = new XMLHttpRequest();
                request.open('GET', '/calendar/ajax/contact_details.php?'+params, true);
                request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                request.onload = function() {
                    if (request.status >= 200 && request.status < 400) {
                        // Success!
                        var data = JSON.parse(request.responseText);
                        console.log(data)
                        document.getElementById('form_code_contact').value = data[0].contact_id;
                        document.getElementById('form_contact_civ').value = data[0].prefix;
                        document.getElementById('form_contact_nom').value = data[0].firstname;
                        document.getElementById('form_contact_prenom').value = data[0].lastname;
                        document.getElementById('form_contact_adr1').value = data[0].addr1;
                        document.getElementById('form_contact_cp').value = data[0].zip_code;
                        document.getElementById('form_contact_adr2').value = data[0].addr2;
                        document.getElementById('form_contact_ville').value = data[0].city;
                        document.getElementById('form_contact_adr3').value = data[0].addr3;
                        document.getElementById('form_contact_mail').value = data[0].email;
                        document.getElementById('form_contact_tel1').value = data[0].tel1;
                        document.getElementById('form_contact_tel2').value = data[0].tel2;
                        document.getElementById('form_contact_tel3').value = data[0].tel3;

                    } else {
                        // We reached our target server, but it returned an error

                    }
                };
                request.onerror = function() {
                    // There was a connection error of some sort

                };
                request.send();
            }

            node.childNodes[2].firstElementChild.value;

            console.log('set value')
            console.log(contact_id)
            // Champs hidden contact_id du formulaire de création d'events
            node.childNodes[1].value = contact_id;
            // Champs hidden user_id du formulaire de création d'events
            node.childNodes[2].value = parseInt(document.getElementsByName('seller')[0].value);
            console.log('userId= ' + node.childNodes[2].value);
            // Champs contact_name du formulaire de création d'events
            node.childNodes[4].value = "";

        },
        // ENREGISTREMENT TAF
        get_value:function(node, ev){

            ev.details = node.childNodes[4].value;
            console.log('get')
            return node.childNodes[1].value;
        },
        focus:function(node){
            var a = node.childNodes[1];
            a.select();
            a.focus();
        }
    };

    //CREATE FORM
    scheduler.config.lightbox.sections=[
        {name:"title", height:46, map_to:"text", type:"textarea" },
        {name: "type", height: 40, map_to: "type", type: "select",
            options: scheduler.serverList("type")},
        {name:"contact", height:200, map_to:"contact", type:"my_editor"},
        {name:"description", height:100, map_to:"details", type:"textarea" },
        {name: "recurring", type: "recurring", map_to: "rec_type", button: "recurring"},
        {name: "time", height: 72, type: "time", map_to: "auto"},
    ];

    // Date du jour
    var dateNow = Date.now();
    // CREATE SCHEDULER
    scheduler.init('scheduler_here', new Date(dateNow) ,"month");
    scheduler.setLoadMode("month");
    scheduler.load("../calendar/data/events_shared.php?user_id");

    scheduler.config.full_day = true;
    scheduler.locale.labels.section_location="Location";
    scheduler.config.details_on_create=true;
    scheduler.config.details_on_dblclick=true;

    dp = new dataProcessor("../calendar/data/events_shared.php");
    dp.enableDebug(true);
    dp.init(scheduler);
    dp.enableDebug(true);

}

function doOnLoad() {
    var formData = [
        {type: "settings", position: "label-left", labelWidth: 110, inputWidth: 200},
        {type: "combo", label: "contact", name: "contact_name", serverFiltering: "../calendar/ajax/contact.php", filterCache: true},
    ];
    myForm = new dhtmlXForm("my-select-contact", formData);

    var formData2 = [
        {type: "settings", position: "label-left", labelWidth: 110, inputWidth: 1200},
        {type: "combo", label: "seller", name: "seller", serverFiltering: "../calendar/ajax/seller.php", filterCache: true},
    ];
    myForm2 = new dhtmlXForm("my-select-seller", formData2);
}

function changeCalendar(user_id) {
    if (user_id != null){
        console.log('change-user-to : user_id= '+user_id)
        var dateNow = Date.now();
        // UPDATE SCHEDULER
        scheduler.init('scheduler_here', new Date(dateNow) ,"month");
        scheduler.config.details_on_dblclick = true;
        dp.serverProcessor = ("../calendar/data/events_shared.php?user_id="+user_id);
        scheduler.clearAll();
        scheduler.load("../calendar/data/events_shared.php?user_id="+user_id);

    }
}