"use strict";

(function () {
  var message = document.querySelector('.mensajes');
  if (!message) { return;
  } else {
    animateMessage();
  }
  message.addEventListener('animationend', removeClass);
  function animateMessage() {
    message.classList.add('is-animated');
  }
  function removeClass() {
    message.classList.remove('is-animated');
  }

  var trigger = document.querySelectorAll('.acceptance-trigger');
  var acceptance = document.querySelector('#acceptance');

  if (!trigger) { return; }
  var _iteratorNormalCompletion = true;
  var _didIteratorError = false;
  var _iteratorError = undefined;

  try {
    for (var _iterator = trigger[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
      var t = _step.value;
      t.addEventListener('click', toggleAcceptance);
    }
  } catch (err) {
    _didIteratorError = true;_iteratorError = err;
  } finally {
      try {
        if (!_iteratorNormalCompletion && _iterator.return != null) {
          _iterator.return();
        }
      } finally {
        if (_didIteratorError) {
          throw _iteratorError;
        }
      }
  }

  function toggleAcceptance(e) {
    e.preventDefault();
    if (acceptance.clientHeight > 0) {
      acceptance.style.height = '0px';
      return;
    }
    var height = acceptance.querySelector('.acceptance__container').clientHeight; acceptance.style.height = height + 'px';
  }
})();

(function () {
      var triggers = document.querySelectorAll('.dropdown__trigger');
      var panels = document.querySelectorAll('.dropdown__panel');
      var _iteratorNormalCompletion = true; var _didIteratorError = false;
      var _iteratorError = undefined;
      try {
        for (var _iterator = triggers[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
          var t = _step.value;
          t.addEventListener('click', toggleDropdown);
        }
      } catch (err) {
        _didIteratorError = true;
        _iteratorError = err;
      } finally {
        try {
          if (!_iteratorNormalCompletion && _iterator.return != null) {
            _iterator.return();
          }
        } finally {
          if (_didIteratorError) {
            throw _iteratorError;
          }
        }
      }

      function toggleDropdown(e) {
          e.preventDefault();
          var target = e.target.closest('a').getAttribute('href');
          var panel = document.querySelector(target);
          var container = panel.closest('.dropdown');
          if (panel.classList.contains('is-active')) {
            panel.classList.toggle('is-active');
            container.classList.toggle('is-active');
          } else {
            var _iteratorNormalCompletion2 = true;
            var _didIteratorError2 = false;
            var _iteratorError2 = undefined;

            try {
              for (var _iterator2 = panels[Symbol.iterator](), _step2; !(_iteratorNormalCompletion2 = (_step2 = _iterator2.next()).done); _iteratorNormalCompletion2 = true) {
                var p = _step2.value;
                p.classList.remove('is-active');
                p.closest('.dropdown').classList.remove('is-active');
              }
            } catch (err) {
              _didIteratorError2 = true;
              _iteratorError2 = err;
            } finally {
              try {
                if (!_iteratorNormalCompletion2 && _iterator2.return != null) {
                  _iterator2.return();
                }
              } finally {
                if (_didIteratorError2) {
                  throw _iteratorError2;
                }
              }
            }
            setPanelWidth(panel);
          }
      }

      function setPanelWidth(panel) {
        panel.style.opacity = '0';
        panel.classList.add('is-active');
        var windowWidth = window.innerWidth;
        var container = panel.closest('.dropdown');
        var panelWidth = panel.offsetWidth;
        container.classList.add('is-active'); // Hallamos el extremo derecho del contenedor

        var containerWidth = container.offsetWidth;
        var containerOffsetRight = container.offsetLeft + containerWidth; // Si el panel es más pequeño que la distancia hasta el extremo derecho, es que cabe en la pantalla

        if (panelWidth < containerOffsetRight) {
          panel.style.opacity = '1';
          return;
        } // Reposicionamos el panel por la derecha ( 0 - distancia hasta el borde de la pantalla )


        panel.style.right = 0 - (window.innerWidth - containerOffsetRight) + 16 + 'px';
        panel.style.opacity = '1';
      }

})();

(function () {
  var menuTrigger = document.querySelector('#menu-trigger');
  var menu = document.querySelector('#site-header__menu');
  menuTrigger.addEventListener('click', function (e) {
    e.preventDefault();
    menu.classList.toggle('is-active');
  });
})();

(function(){
	let triggers = document.querySelectorAll('.pop-up-trigger');
	let popupBtnsClose = document.querySelectorAll('.pop-up__btn-close');
	if(!triggers){
		return;
	}
	for(let t of triggers){
		t.addEventListener('click', openPopUp)
	}
	for(let b of popupBtnsClose){
		b.addEventListener('click', closePopUp)
	}
	function openPopUp(e){
		e.preventDefault();
    let popupID = e.target.getAttribute('href');
		let popup = document.querySelector(popupID);
		if(!popup){return;}
		let header = e.target.closest('.draw').querySelector('.card__header').innerHTML;
		popup.querySelector('.card__header').innerHTML = header;
		popup.classList.toggle('is-visible');
	}
	function closePopUp(e){
		e.preventDefault();
		let popUp = e.target.closest('.pop-up');
		popUp.classList.toggle('is-visible');
		popUp.querySelector('.card__header').innerHTML = '';
	}

})()
