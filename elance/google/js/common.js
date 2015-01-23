function makeAutoComplete(tid,options){
    var text = (document.getElementById(''+tid+''));
    var autocomplete = new google.maps.places.Autocomplete(text,options);
}