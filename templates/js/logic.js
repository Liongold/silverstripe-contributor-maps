//GMap
var map;
//Marker[]
var markerArray = new Array();
//Skills[]
var skillArray = new Array();
//Checkbox[]
var checkboxes;
//GeoCoder
var geocoder;
function initialize() {
    //Get all the checkboxes in an array
    checkboxes = {
        Base_Mobile: document.getElementsByName("Base")[0],
        Calc_Mobile: document.getElementsByName("Calc")[0],
        Design_Mobile: document.getElementsByName("Design")[0],
        Development_Mobile: document.getElementsByName("Development")[0],
        Documentation_Mobile: document.getElementsByName("Documentation")[0],
        Draw_Mobile: document.getElementsByName("Draw")[0],
        Impress_Mobile: document.getElementsByName("Impress")[0],
        Infrastructure_Mobile: document.getElementsByName("Infrastructure")[0],
        Localisation_Mobile: document.getElementsByName("Localisation")[0],
        Marketing_Mobile: document.getElementsByName("Marketing")[0],
        Math_Mobile: document.getElementsByName("Math")[0],
        Quality_Assurance_Mobile: document.getElementsByName("Quality_Assurance")[0],
        Writer_Mobile: document.getElementsByName("Writer")[0],
        Base: document.getElementsByName("Base")[1],
        Calc: document.getElementsByName("Calc")[1],
        Design: document.getElementsByName("Design")[1],
        Development: document.getElementsByName("Development")[1],
        Documentation: document.getElementsByName("Documentation")[1],
        Draw: document.getElementsByName("Draw")[1],
        Impress: document.getElementsByName("Impress")[1],
        Infrastructure: document.getElementsByName("Infrastructure")[1],
        Localisation: document.getElementsByName("Localisation")[1],
        Marketing: document.getElementsByName("Marketing")[1],
        Math: document.getElementsByName("Math")[1],
        Quality_Assurance: document.getElementsByName("Quality_Assurance")[1],
        Writer: document.getElementsByName("Writer")[1]
    };
    //Make map
    //map = L.map('map-canvas').setView([50.2612094, 10.962695],1);
    map = L.map('map-canvas', { worldCopyJump:true }).setView([0, 0],2);
    L.tileLayer('https://a.tiles.mapbox.com/v3/mapbox.world-bright/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
        minZoom: 2,
        maxZoom: 11
    }).addTo(map);
    //Create geocoder
    geocoder = new google.maps.Geocoder();
    //Create markers
    data.forEach(function (entry) {
        // console.log(entry);
        var html = [];
        html.push("<div id='gpopup'><h2><a href='mailto:" + entry.email + "'>" + entry.name + "</a></h2><p>" + entry.location + "</p><div id='desc'><ul>");
        var skills = entry.skills;
        if(skills.Design)
            html.push('<li>','Design', '</li>')
        if(skills.Development)
            html.push('<li>','Development', '</li>')
        if(skills.Documentation)
            html.push('<li>','Documentation', '</li>')
        if(skills.Infrastructure)
            html.push('<li>','Infrastructure', '</li>')
        if(skills.Localisation)
            html.push('<li>','Localisation', '</li>')
        if(skills.Marketing)
            html.push('<li>','Marketing', '</li>')
        if(skills.Quality_Assurance)
            html.push('<li>','Quality Assurance', '</li>')
        if(skills.Base)
            html.push('<li>','Support - Base', '</li>')
        if(skills.Calc)
            html.push('<li>','Support - Calc', '</li>')
        if(skills.Draw)
            html.push('<li>','Support - Draw', '</li>')
        if(skills.Impress)
            html.push('<li>','Support - Impress', '</li>')
        if(skills.Math)
            html.push('<li>','Support - Math', '</li>')
        if(skills.Writer)
            html.push('<li>','Support - Writer', '</li>')
        html.push("</ul></div></div>");
        geocoder.geocode({ address: entry.location }, function (result, status) {
            if (status == google.maps.GeocoderStatus.OK){
                var marker = L.marker([result[0].geometry.location.lat(),result[0].geometry.location.lng()]).addTo(map).bindPopup(html.join(""));
                markerArray.push(marker);
                skillArray.push(skills);
            } else {
                //Geocode state not OK
                console.log("Geocode status for " + entry.name + " is not valid. Location=" + entry.location);
            }
        });
    });
    //Update markers
    updateMarkers();
} // end initialize
function checkAll() {
    checkboxes.Base_Mobile.checked = true
    checkboxes.Calc_Mobile.checked = true
    checkboxes.Design_Mobile.checked = true
    checkboxes.Development_Mobile.checked = true
    checkboxes.Documentation_Mobile.checked = true
    checkboxes.Draw_Mobile.checked = true
    checkboxes.Impress_Mobile.checked = true
    checkboxes.Infrastructure_Mobile.checked = true
    checkboxes.Localisation_Mobile.checked = true
    checkboxes.Marketing_Mobile.checked = true
    checkboxes.Math_Mobile.checked = true
    checkboxes.Quality_Assurance_Mobile.checked = true
    checkboxes.Writer_Mobile.checked = true
    checkboxes.Base.checked = true
    checkboxes.Calc.checked = true
    checkboxes.Design.checked = true
    checkboxes.Development.checked = true
    checkboxes.Documentation.checked = true
    checkboxes.Draw.checked = true
    checkboxes.Impress.checked = true
    checkboxes.Infrastructure.checked = true
    checkboxes.Localisation.checked = true
    checkboxes.Marketing.checked = true
    checkboxes.Math.checked = true
    checkboxes.Quality_Assurance.checked = true
    checkboxes.Writer.checked = true
    updateMarkers();
}
function unCheckAll() {
    checkboxes.Base_Mobile.checked = false
    checkboxes.Calc_Mobile.checked = false
    checkboxes.Design_Mobile.checked = false
    checkboxes.Development_Mobile.checked = false
    checkboxes.Documentation_Mobile.checked = false
    checkboxes.Draw_Mobile.checked = false
    checkboxes.Impress_Mobile.checked = false
    checkboxes.Infrastructure_Mobile.checked = false
    checkboxes.Localisation_Mobile.checked = false
    checkboxes.Marketing_Mobile.checked = false
    checkboxes.Math_Mobile.checked = false
    checkboxes.Quality_Assurance_Mobile.checked = false
    checkboxes.Writer_Mobile.checked = false
    checkboxes.Base.checked = false
    checkboxes.Calc.checked = false
    checkboxes.Design.checked = false
    checkboxes.Development.checked = false
    checkboxes.Documentation.checked = false
    checkboxes.Draw.checked = false
    checkboxes.Impress.checked = false
    checkboxes.Infrastructure.checked = false
    checkboxes.Localisation.checked = false
    checkboxes.Marketing.checked = false
    checkboxes.Math.checked = false
    checkboxes.Quality_Assurance.checked = false
    checkboxes.Writer.checked = false
    updateMarkers();
}
function isThisShown(skills) {
    if(document.getElementsByClassName("toc-mobile")[0].style.display === "block") {
        var b = (checkboxes.Base_Mobile.checked && skills.Base)
                || (checkboxes.Calc_Mobile.checked && skills.Calc)
                || (checkboxes.Design_Mobile.checked && skills.Design)
                || (checkboxes.Development_Mobile.checked && skills.Development)
                || (checkboxes.Documentation_Mobile.checked && skills.Documentation)
                || (checkboxes.Draw_Mobile.checked && skills.Draw)
                || (checkboxes.Impress_Mobile.checked && skills.Impress)
                || (checkboxes.Infrastructure_Mobile.checked && skills.Infrastructure)
                || (checkboxes.Localisation_Mobile.checked && skills.Localisation)
                || (checkboxes.Marketing_Mobile.checked && skills.Marketing)
                || (checkboxes.Math_Mobile.checked && skills.Math)
                || (checkboxes.Quality_Assurance_Mobile.checked && skills.Quality_Assurance)
                || (checkboxes.Writer_Mobile.checked && skills.Writer);
    }else{
        var b = (checkboxes.Base.checked && skills.Base)
                || (checkboxes.Calc.checked && skills.Calc)
                || (checkboxes.Design.checked && skills.Design)
                || (checkboxes.Development.checked && skills.Development)
                || (checkboxes.Documentation.checked && skills.Documentation)
                || (checkboxes.Draw.checked && skills.Draw)
                || (checkboxes.Impress.checked && skills.Impress)
                || (checkboxes.Infrastructure.checked && skills.Infrastructure)
                || (checkboxes.Localisation.checked && skills.Localisation)
                || (checkboxes.Marketing.checked && skills.Marketing)
                || (checkboxes.Math.checked && skills.Math)
                || (checkboxes.Quality_Assurance.checked && skills.Quality_Assurance)
                || (checkboxes.Writer.checked && skills.Writer);
    }
    b = Boolean(b);
    return b;
}
function updateMarkers() {
    for (var i = 0; i < markerArray.length; i++) {
        if (isThisShown(skillArray[i]))
            map.addLayer(markerArray[i]);
        else
            map.removeLayer(markerArray[i]);
    }
}
function showEntryForm(called) {
    if(!called) {
        showEditForm(true);
    }
    var div = document.getElementById("cmaps_registration");
    if(div.style.display === "block" || called) {
        div.style.display = "none";
    }else{
        div.style.display = "block";
    }
}
function openSideBar() {
    var div = document.getElementsByClassName("toc-mobile")[0];
    if(div.style.right === "0px") {
        div.style.right = "-100%";
        document.getElementById("blanket-mobile").style.display = "none";
    }else{
        div.style.right = 0;
        document.getElementById("blanket-mobile").style.display = "block";
    }
}
function showEditForm(called) {
    if(!called) {
        showEntryForm(true);
    }
    var div = document.getElementById("cmaps_editemail");
    if(div.style.display === "block" || called) {
        div.style.display = "none";
    }else{
        div.style.display = "block";
    }
}
function warningDelete(e) {
    if(e.target.id === "Form_RequestEditForm_action_processDeleteRequestForm") {
        if(!document.getElementById("cmaps_warning_checkbox").checked) {
            document.getElementById("cmaps_deletewarning").style.display = "block";
            document.getElementById("cmaps_editemail").style.height = "350px";
            e.preventDefault();
            return false;
        }else{
            alert("test");
        }
    }
}
function checkLocation(e) {
    e.preventDefault();
    e.stopPropagation();
    var newlocation = document.getElementById("Form_RegistrationForm_Location").value;
    if(newlocation !== current) {
        geocoder.geocode({address:newlocation}, function(results, status) {
            if(status == google.maps.GeocoderStatus.ZERO_RESULTS) {
                var elem = document.getElementById("Location_Error");
                if(elem) {
                    elem.parentNode.removeChild(elem);
                }
                var span = document.createElement("span");
                span.className = "message bad";
                span.id = "Location_Error";
                span.innerHTML = "This location seems to be invalid. Please check the location and try again.";
                document.getElementById("Location").appendChild(span);
                document.getElementById("Email").scrollIntoView();
                return false;
            }else{
                document.getElementById("Form_RegistrationForm").submit();
            }
        });
    }
}
window.onload = function(){
    var notification = document.getElementById("cmaps_notification");
    if(notification) {
        $("#cmaps_notification").fadeOut(7500); //jQuery
    }
    // jQuery
    $(document).keyup(function(e) {
        if(e.keyCode == 27) {
            if(document.getElementById("cmaps_registration").style.display === "block") {
                showEntryForm();
            }else if(document.getElementById("cmaps_editemail").style.display === "block") {
                showEditForm();
            }
        }
    });
    document.getElementById("blanket-mobile").addEventListener('click', openSideBar, false);
    document.getElementById("Form_RequestEditForm_action_processDeleteRequestForm").addEventListener("click", warningDelete, false);
    if(document.getElementById("Form_RegistrationForm_action_processForm")) {
        document.getElementById("Form_RegistrationForm_action_processForm").addEventListener("click", checkLocation, false);
    }else if(document.getElementById("Form_RegistrationForm_action_processEditForm")) {
        document.getElementById("Form_RegistrationForm_action_processEditForm").addEventListener("click", checkLocation, false);
    }
};
google.maps.event.addDomListener(window, 'load', initialize);
