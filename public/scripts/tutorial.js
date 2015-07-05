
function startTutorial() {
  var intro = introJs();
  intro.setOptions({
    steps: [
      {
        intro: 'Tere tulemast Brainstormingu lehele!'
      },
      {
        element: $('#ideas-list > li:first')[0],
        intro: 'Siit kaudu saad uue idee lisada.'
      },
      {
        element: $('#filter-section')[0],
        intro: 'Filtrid on sulle abiks, et olemasolevatest ideedest paremat ülevaadet saada.'
      },
      {
        element: $('#headerProfile p')[0],
        intro: 'Siit saad järge pidada, mitu vaba häält sul veel alles on ideedele andmiseks.'
      }
    ],
    tooltipPosition: 'auto'
  });

  intro.start();
}
