var $ = jQuery;





document.domain = "mapeditor.arhamsoft.info";







/* ////////////////////////////////////////////////////////////////////////////// */

/* ////////////////////////////////////////////////////////////////////// VAR /// */

/* ////////////////////////////////////////////////////////////////////////////// */



/* ---------------------------------------------------------------------- APP --- */

var MAPBOX_TOKEN = 'pk.eyJ1IjoiemFpYm1hc2hhZCIsImEiOiJja3QxaTNmMWYwNG1uMzByMXBkeGxyYzNlIn0.dfrxGDr92Sla189KhGPkfQ';

var MAPBOX = {

    token: MAPBOX_TOKEN,

};



var APP = jQuery({});

    APP.iframe = false;

    APP.support = false;

    APP.render = false;

    APP.ready = false;

    APP.basemap = {

        main: 'label',

        enable: true,

    };

    APP.store = {

        timer: undefined,

    };



/* ---------------------------------------------------------------------- API --- */

var API = {};

    API.root = 'https://mapeditor.arhamsoft.info/wp-admin/admin-ajax.php';





/* ------------------------------------------------------------------- MAPBOX --- */

var MAPBOX = {};

    MAPBOX.token = MAPBOX_TOKEN;

    MAPBOX.style = {

        basemap: 'zaibmashad/cktgakgzr0x9d18wbux5erbgd',

        black_white: 'zaibmashad/cktgasv8w38st18pmkltf2hmw',

        blue: 'zaibmashad/cktgarp7d0ute17pfvhaemweb',

        grey_dark: 'zaibmashad/cktgaqqr042qi18qrn6kaoqlr',

        grey_light: 'zaibmashad/cktgail7a19ag17r4iexbs9sb',

        orange: 'zaibmashad/cktgalz9a1zhi17mo9vpk3ks2',

        outdoor: 'zaibmashad/cktgan9480unb18p97mx40qab',

        pastel: 'zaibmashad/cktgaosrp0uoq18p9u45wh9fc',

        spring: 'zaibmashad/cktgaprsq409w17n7xglu8y34',

    };



/* ------------------------------------------------------------------- STRAVA --- */

var STRAVA = {};

    STRAVA.token = undefined;

    STRAVA.exipry = undefined;

    STRAVA.window = undefined;

    STRAVA.activity = [];



/* ------------------------------------------------------------------ WORDPRESS --- */

var WORDPRESS = {};

    WORDPRESS.product = undefined;



/* ------------------------------------------------------------------ BASEMAP --- */

var BASEMAP = {};



/* ------------------------------------------------------------------- DESIGN --- */

var DESIGN = {};

    DESIGN.guid = undefined;

    DESIGN.paper = {

        material: 'framed_poster',

        size: '20x30',

        orientation: 'portrait',

    };

    DESIGN.poster = {

        style: 'grey_light',

    };

    DESIGN.font = {

       family: 'circular',

       size: 'medium',

    };

    DESIGN.layer = {};

    DESIGN.layer.map = {

		zoom: 6.5,

		center: [52.221, 5.281,],

		bound: [[52.12, 46.77,], [5.16, 45.73,],],

	};

    DESIGN.layer.overlay = {

        type: 'none',

    };

    DESIGN.layer.activity = {

        item: [],

        line_width: 3,

        point_finish: false,

        point_activity: false,

    };

    DESIGN.layer.label = {

        item: [],

    };

    DESIGN.layer.outline = {

        type: 'none',

    };
    
    DESIGN.layer.framed_poster = {

        type: 'black',

    };


    DESIGN.layer.elevation = {

        enable: false,

        multiply: 'small',

    };

    DESIGN.layer.text = {

        headline: '',

        subtitle: '',

        footnote: '',

        metadata: '',

    };

