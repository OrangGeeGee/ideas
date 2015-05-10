// moment.js language configuration
// language : estonian (et)
// author : Henry Kehlmann : https://github.com/madhenry

(function (factory) {
    factory(moment);
}(function (moment) {
    function translateSeconds(number, withoutSuffix, key, isFuture) {
        return (isFuture || withoutSuffix) ? 'paari sekundi' : 'paar sekundit';
    }

    return moment.lang('et', {
        months        : "jaanuar veebruar märts aprill mai juuni juuli august september oktoober november detsember".split(" "),
        monthsShort   : "jaan veebr märts apr mai juuni juuli aug sept okt nov dets".split(" "),
        weekdays      : "pühapäev esmaspäev teisipäev kolmapäev neljapäev reede laupäev".split(" "),
        weekdaysShort : "PETKNRL".split(""),
        weekdaysMin   : "PETKNRL".split(""),
        longDateFormat : {
            LT   : "H:mm",
            L    : "DD.MM.YYYY",
            LL   : "D. MMMM YYYY",
            LLL  : "D. MMMM YYYY LT",
            LLLL : "dddd, D. MMMM YYYY LT"
        },
        calendar : {
            sameDay  : '[Täna,] LT',
            nextDay  : '[Homme,] LT',
            nextWeek : '[Järgmine] dddd LT',
            lastDay  : '[Eile,] LT',
            lastWeek : '[Eelmine] dddd LT',
            sameElse : 'L'
        },
        relativeTime : {
            future : "%s pärast",
            past   : "%s tagasi",
            s      : translateSeconds,
            m      : "minut",
            mm     : "%d minutit",
            h      : "tund",
            hh     : "%d tundi",
            d      : "päev",
            dd     : "%d päeva",
            M      : "kuu",
            MM     : "%d kuud",
            y      : "aasta",
            yy     : "%d aastat"
        },
        ordinal : '%d',
        week : {
            dow : 1, // Monday is the first day of the week.
            doy : 4  // The week that contains Jan 4th is the first week of the year.
        }
    });
}));