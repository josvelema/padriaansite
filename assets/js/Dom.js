class Dom {
  constructor(context = window) {
    this.context = context;
    this.element = null;
    this.elements = [];
    this.animationInterval = new Map();
    if (!window._DomEventMap) window._DomEventMap = new WeakMap();
  }

  /**
   * Check if a node is already contained in the collection
   * @param  {HTMLElement} element
   * @return {Boolean}
   */
  has(element) {
    return this.elements.includes(element);
  }

  /**
   * Add an element or a list of elements to the collection
   * @param   {mixed} element
   * @returns {this}
   */
  add(element) {
    const elements =
      element instanceof this.context.NodeList
        ? Array.from(element)
        : element instanceof Dom
        ? element.elements
        : element instanceof this.context.Array
        ? element
        : [element];

    Array.from(elements).forEach(element => {
      if (element && !this.has(element)) {
        if (!this.element) {
          this.element = element;
        }
        this.elements.push(element);
      }
    });

    return this;
  }

  /**
   * Compares a given DomCollection with 'this' and returns true if all elements match
   * @param   {Dom} dom
   * @returns {Boolean}
   */
  compare(dom) {
    return (
      dom.elements.length == this.elements.length &&
      dom.elements.every((v, i) => v === this.elements[i])
    );
  }

  ///////////////
  // Traversal //
  ///////////////

  /**
   * Find descendants of the current collection matching a selector
   * @param  {String} selector
   * @return {this}
   */
  find(selector) {
    return this.elements.reduce(
      (carry, element) => carry.add(element.querySelectorAll(selector)),
      new Dom(this.context)
    );
  }

  /**
   * Filter the current collection by a selector or filter function
   * @param  {String|Function} selector
   * @return {this}
   */
  filter(selector) {
    return new Dom(this.context).add(
      this.elements.filter(
        typeof selector === 'function' ? selector : element => element.matches(selector)
      )
    );
  }

  /**
   * Get a collection containing the adjecent next siblings
   * of the current collection, optionally filtered by a selector
   * @param  {String|undefined} selector
   * @return {this}
   */
  next(selector) {
    return new Dom(this.context).add(
      this.elements
        .map(element => element.nextElementSibling)
        .filter(element => element && (!selector || element.matches(selector)))
    );
  }

  /**
   * Get a collection containing the adjecent previous siblings
   * of the current collection, optionally filtered by a selector
   * @param  {String|undefined} selector
   * @return {this}
   */
  prev(selector) {
    return new Dom(this.context).add(
      this.elements
        .map(element => element.previousElementSibling)
        .filter(element => element && (!selector || element.matches(selector)))
    );
  }

  /**
   * Get a collection conaining all siblings of the current collection
   * @param {String|unidentified} selector
   * @return {this}
   */
  siblings(selector) {
    var sibs = new Dom(this.context);
    this.elements.forEach(collectionElement => {
      let element = collectionElement.parentNode.firstChild;
      do {
        if (
          element.nodeType != 3 && // text node
          element != collectionElement && // source node
          (!selector || element.matches(selector))
        )
          sibs.add(element);
      } while ((element = element.nextSibling));
    });
    return sibs;
  }

  /**
   * Get a collection containing the parents of
   * the current collection, optionally filtered by a selector
   * @param  {String|undefined} selector
   * @return {this}
   */
  parent(selector) {
    return new Dom(this.context).add(
      this.elements.map(function walk(element) {
        const parent = element.parentNode;
        return !parent || !parent.matches
          ? false
          : !selector || parent.matches(selector)
          ? parent
          : walk(parent);
      })
    );
  }

  /**
   * Get a collection containing all the the parents of
   * the current collection, optionally filtered by a selector
   * @param  {String|undefined} selector
   * @return {this}
   */
  parents(selector) {
    let parents = this.parent();
    let result = new Dom(this.context);
    while (parents.length()) {
      result.add(parents);
      parents = parents.parent();
    }
    return result.filter(selector);
  }

  /**
   * Get a collection containing the immediate children of the
   * current collection, optionally filtered by a selector
   * @param  {String|undefined} selector
   * @return {this}
   */
  children(selector) {
    return new Dom(this.context).add(
      this.elements
        .reduce((carry, element) => carry.concat(...element.children), [])
        .filter(element => !selector || element.matches(selector))
    );
  }

  //////////////////
  // Manipulation //
  //////////////////

  /**
   * Add a class to all elements in the current collection
   * @param   {...String} className
   * @returns {this}
   */
  addClass(...className) {
    className.length && this.elements.forEach(element => element.classList.add(...className));
    return this;
  }

  /**
   * Remove a class from all elements in the current collection
   * @param  {...String} className
   * @return {this}
   */
  removeClass(...className) {
    this.elements.forEach(element => element.classList.remove(...className));
    return this;
  }

  /**
   * Returns boolean that indicates if given className is presnt in th classlist
   * of the first element in the current collection
   * @param   {String} className
   * @returns {String}
   */
  hasClass(className) {
    return this.element && this.element.classList.contains(className);
  }

  /**
   * Toggles css class on or off on all elements in the current collection
   * of the first element in the current collection
   * @param   {...String} className
   * @returns {this}
   */
  toggleClass(className) {
    this.hasClass(className) ? this.removeClass(className) : this.addClass(className);
    return this;
  }

  /**
   * Set the value property of all elements in the current
   * collection, or, if no value is specified, get the value
   * of the first element in the collection
   * @param  {mixed} newVal
   * @return {this}
   */
  value(newValue) {
    if (typeof newValue === 'undefined') {
      return this.element.value;
    }
    this.elements.forEach(element => (element.value = newValue));
    return this;
  }

  /**
   * Set the HTML of all elements in the current collection,
   * or, if no markup is specified, get the HTML of the first
   * element in the collection
   * @param  {String|undefined} newHtml
   * @return {this}
   */
  html(newHTML) {
    if (typeof newHTML === 'undefined') {
      return this.element.innerHTML;
    }
    this.elements.forEach(element => (element.innerHTML = newHTML));
    return this;
  }

  /**
   * Shortcut function to empty all elements in current
   * collection
   * @return {this}
   */
  empty() {
    this.html('');
    return this;
  }

  /**
   * Set the text of all elements in the current collection,
   * or, if no markup is specified, get the HTML of the first
   * element in the collection
   * @param  {String|undefined} newText
   * @return {this}
   */
  text(newText) {
    if (typeof newText === 'undefined') {
      return this.element && this.element.textContent;
    }
    this.elements.forEach(element => (element.textContent = newText));
    return this;
  }

  /**
   * When editing an XML DOM with this class, you can set a cdata
   * section with this function
   * @param  {String|undefined} newText
   * @return {this}
   */
  cdata(newText) {
    if (typeof newText === 'undefined') {
      return this.element && this.element.textContent;
    }
    const cdata = new Document().createCDATASection(newText);
    this.elements.forEach(element =>
      element.firstChild
        ? element.replaceChild(cdata, element.firstChild)
        : element.appendChild(cdata)
    );
    return this;
  }

  /**
   * Set the specified attribute of the elements in the
   * current collection by either specifying the attribute
   * and value, or an object containing the attribute names
   * and values
   * @param  {String|object} style
   * @param  {mixed} value
   * @return {this}
   */
  attr(variable, value) {
    const attributes = {};

    if (typeof variable === 'string') {
      if (typeof value === 'undefined') {
        return this.element && this.element.getAttribute(variable);
      } else if (value === null) {
        this.elements.forEach(element => element.removeAttribute(variable));
        return this;
      }
      attributes[variable] = value;
    } else {
      Object.assign(attributes, variable);
    }

    this.elements.forEach(element =>
      Object.entries(attributes).forEach(attr => element.setAttribute(attr[0], attr[1]))
    );

    return this;
  }

  /**
   * Call the specified function on all elements in the
   * current collection
   * @param  {String} fn
   * @return {this}
   */
  call(fn) {
    this.elements.forEach(element =>
      typeof element[fn] === 'function' ? element[fn].call(element) : false
    );
    return this;
  }

  /**
   * Set the specified properties of the elements in the
   * current collection by either specifying the property
   * and value, or an object containing the property names
   * and values
   * @param  {String|object} variable
   * @param  {mixed} value
   * @return {this}
   */
  prop(variable, value) {
    const properties = {};

    if (typeof variable === 'string') {
      if (typeof value === 'undefined') {
        return this.element && this.element[variable];
      }
      properties[variable] = value;
    } else {
      Object.assign(properties, variable);
    }

    this.elements.forEach(element => Object.assign(element, properties));

    return this;
  }

  /**
   * Set a window global data variable connected
   * to the first element in the current collection
   * @param {String} variable
   * @param {mixed} value
   * @return {this}
   */
  data(variable, value) {
    if (!this.element) return;
    if (!window._DomData) {
      window._DomData = new WeakMap();
    }

    const data = window._DomData.get(this.element) || {};

    if (typeof variable === 'string') {
      if (typeof value === 'undefined') {
        return data[variable];
      }
      data[variable] = value;
    } else {
      Object.assign(data, variable);
    }

    window._DomData.set(this.element, data);

    return this;
  }

  /**
   * Append element at the end of every element in
   * current collection
   * @param {Element}
   * @return {this}
   */
  append(element) {
    this.elements.forEach(target =>
      target.insertAdjacentElement('beforeend', element instanceof Dom ? element.element : element)
    );
    return this;
  }

  /**
   * Append elements to the supplied elements
   * current collection
   * @param {Element}
   * @return {this}
   */
  appendTo(target) {
    this.elements.forEach(element => target.element.insertAdjacentElement('beforeend', element));
    return this;
  }

  /**
   * Prepend element to the beginning of every element in
   * current collection
   * @param {Element}
   * @return {this}
   */
  prepend(element) {
    this.elements.forEach(target =>
      target.insertAdjacentElement('afterbegin', element instanceof Dom ? element.element : element)
    );
    return this;
  }

  /**
   * Prepend elements to the supplied elements
   * current collection
   * @param {Element}
   * @return {this}
   */
  prependTo(target) {
    this.elements.forEach(element => target.element.insertAdjacentElement('afterbegin', element));
    return this;
  }

  /**
   * Add element before every element current collection
   * @param {Element}
   * @return {this}
   */
  before(target) {
    this.elements.forEach(element => target.element.insertAdjacentElement('beforebegin', element));
    return this;
  }

  /**
   * Add element after every element current collection
   * @param {Element}
   * @return {this}
   */
  after(target) {
    this.elements.forEach(element => target.element.insertAdjacentElement('afterend', element));
    return this;
  }

  /**
   * Removes all elements in current collection from dom
   * @returns {this}
   */
  remove() {
    this.elements.forEach(element => {
      if (element.parentNode) {
        element = element.parentNode.removeChild(element);
      }
    });
    return this;
  }

  ///////////////////////
  // CSS and animation //
  ///////////////////////

  /**
   * Set the CSS of the elements in the current collection
   * by either specifying the CSS property and value, or
   * an object containing the style declarations
   * @param  {String|object} style
   * @param  {mixed} value
   * @return {this}
   */
  css(style, value) {
    const currentStyle = {};

    if (typeof style === 'string') {
      if (typeof value === 'undefined') {
        return (
          this.element &&
          window
            .getComputedStyle(this.element)
            .getPropertyValue(style)
            .replace(/^([0-9]+)px$/, '$1')
        );
      }
      currentStyle[style] =
        typeof value !== 'number'
          ? value
          : ['opacity', 'z-index'].indexOf(style) >= 0
          ? value
          : value + 'px';
    } else {
      Object.entries(style).forEach(([k, v]) => {
        style[k] =
          typeof v !== 'number' ? v : ['opacity', 'z-index'].indexOf(k) >= 0 ? v : v + 'px';
      });
      Object.assign(currentStyle, style);
    }

    this.elements.forEach(element => Object.assign(element.style, currentStyle));
    return this;
  }

  measure(fn) {
    // Check if element is visible
    let isVisible = el => !!(!el || el.offsetHeight || el.offsetWidth);

    // Expose element but keep it hidden, return css restore function
    let expose = el => {
      if (el.style.display !== 'none') return function() {};
      let before = el.style.cssText;
      Object.assign(el.style, {
        display: 'block',
        position: 'absolute',
        visibility: 'hidden'
      });
      return function() {
        el.style.cssText = before;
      };
    };

    let css_restore_functions = [];
    let parent = this.element;
    while (!isVisible(parent) && parent != document.body) {
      css_restore_functions.push(expose(parent));
      parent = parent.parentNode;
    }

    let result = fn.call(this, false);
    css_restore_functions.map(restore => restore());
    return result;
  }

  /**
   * Get the height including borders and padding of the
   * first element in the current collection
   * @return {this}
   */
  height(measure = false) {
    if (measure) {
      return this.measure(this.height);
    }
    return (
      this.element &&
      (typeof this.element.offsetHeight !== 'undefined'
        ? this.element.offsetHeight
        : this.element.innerHeight)
    );
  }

  /**
   * Get the width including borders and padding of the
   * first element in the current collection
   * @return {this}
   */
  width(measure = false) {
    if (measure) {
      return this.measure(this.width);
    }
    return this.element && (this.element.offsetWidth || this.element.innerWidth);
  }

  /**
   * Get the offset relative to the body of the first element in the collection
   * first element in the current collection
   * @return {object}
   */
  offset() {
    var rec = this.element.getBoundingClientRect();
    return { top: rec.top + window.scrollY, left: rec.left + window.scrollX };
  }

  /**
   * Animate a css property of the first element of the
   * current collection
   * @param {String} property
   * @param {Int} duration
   * @param {String} easing
   * @return {Promise}
   */
  animate(property, toValue, duration = 300, easing = 'easeInOut') {
    // Handle empty collections
    if (this.length() === 0) {
      return new Promise(resolve => {
        resolve();
      });
    }

    let easingFunction;
    switch (easing) {
      case 'linear':
        easingFunction = t => t;
        break;
      case 'easeIn':
        easingFunction = t => 1 + Math.sin((Math.PI / 2) * t - Math.PI / 2);
        break;
      case 'easeOut':
        easingFunction = t => Math.sin((Math.PI / 2) * t);
        break;
      default:
      case 'easeInOut':
        easingFunction = t => (1 + Math.sin(Math.PI * t - Math.PI / 2)) / 2;
        break;
      case 'easeInQuad':
        easingFunction = t => t * t;
        break;
      case 'easeOutQuad':
        easingFunction = t => t * (2 - t);
        break;
      case 'easeInOutQuad':
        easingFunction = t => (t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t);
        break;
      case 'easeInElastic':
        easingFunction = t => ((0.04 * t) / --t) * Math.sin(25 * t);
        break;
      case 'easeOutElastic':
        easingFunction = t => (0.04 - 0.04 / t) * Math.sin(25 * t) + 1;
        break;
      case 'easeInOutElastic':
        easingFunction = t =>
          (t -= 0.5) < 0
            ? (0.02 + 0.01 / t) * Math.sin(50 * t)
            : (0.02 - 0.01 / t) * Math.sin(50 * t) + 1;
        break;
    }

    let start = Date.now();
    let target = this.element;
    let fromValue = parseFloat(window.getComputedStyle(target).getPropertyValue(property));
    let delta = toValue - fromValue;
    let unit = property == 'opacity' ? 0 : 'px';

    if (property == 'rotate') {
      const tr = window.getComputedStyle(target).getPropertyValue('transform');
      const values = tr.split('(')[1].split(')')[0].split(',');
      fromValue = Math.round(Math.atan2(values[1], values[0]) * (180/Math.PI));
      unit = 'deg';
      delta = toValue - fromValue;
    }

    if (this.animationInterval.has(property)) {
      clearInterval(this.animationInterval.get(property));
      this.animationInterval.delete(property);
    }

    return new Promise(resolve => {
      this.animationInterval.set(
        property,
        setInterval(() => {
          let step = (Date.now() - start) / duration;
          if (step >= 1) step = 1;
          let position = fromValue + delta * easingFunction(step);
          if (property == 'rotate') {
            target.style['transform'] = 'rotate(' + (position + unit) + ')';
          } else {
            target.style[property] = position + unit;
          }
          if (step == 1) {
            clearInterval(this.animationInterval.get(property));
            this.animationInterval.delete(property);
            resolve();
          }
        }, 5)
      );
    });
  }

  ////////////
  // Events //
  ////////////

  /**
   * Bind event listeners to all elements in the current collection
   * @param  {String} type
   * @param  {Function|String} target
   * @return {this}
   */
  on(type, callback) {
    this.elements.forEach(element => {
      let events = window._DomEventMap.get(element);
      if (!events) {
        window._DomEventMap.set(element, {});
        events = window._DomEventMap.get(element);
      }
      events[type] = events[type] || [];
      events[type].push(callback);
      element.addEventListener(type, callback);
    });
    return this;
  }

  /**
   * Fire a previously defined event on all elements in the collection
   * @param {String} type
   * return {this}
   */
  fire(type) {
    this.elements.forEach(element => {
      const events = window._DomEventMap.get(element);
      const callbacks = events && events[type];
      if (callbacks) callbacks.map(callback => callback.call());
    });
    return this;
  }

  /**
   * Remove event listeners from the elements in the current
   * collection; if no handler is specified, all listeners of
   * the given type will be removed
   * @param  {String} type
   * @param  {Function|undefined} callback
   * @return {this}
   */
  off(type, callback) {
    this.elements.forEach(element => {
      const events = window._DomEventMap.get(element);
      const callbacks = events && events[type];

      if (callback) {
        element.removeEventListener(type, callback);

        if (callbacks) {
          events[type] = callbacks.filter(current => current !== callback);
        }
      } else if (callbacks) {
        delete events[type];

        callbacks.forEach(callback => {
          element.removeEventListener(type, callback);
        });
      }
    });

    return this;
  }

  ///////////////////
  // Miscellaneous //
  ///////////////////

  /**
   * Focus on the first element in the current collection
   * @return {this}
   */
  focus() {
    if (this.element && this.element.focus) this.element.focus();
    return this;
  }

  /**
   * Blur the first element in the current collection
   * @return {this}
   */
  blur() {
    if (this.element && this.element.blur) this.element.blur();
    return this;
  }

  /**
   * Select the text in the first element of the current collection
   * @return {this}
   */
  select() {
    if (this.element && this.element.select) this.element.select();
    return this;
  }

  /**
   * Execute a funtion on each element in the current collection
   * @param  {Function} fn
   * @return {this}
   */
  each(fn, alternateThis) {
    this.elements.forEach(element =>
      fn.call(alternateThis || this, new Dom(this.context).add(element))
    );
    return this;
  }

  /**
   * Get first element in collection
   * @returns {Element}
   */
  first() {
    return new Dom(this.context).add(this.element);
  }

  /**
   * Get last element in collection
   * @returns {Element}
   */
  last() {
    return new Dom(this.context).add(this.elements[this.elements.length - 1]);
  }

  length() {
    return this.elements.length;
  }
}

export default function dom(selector, context = window) {
  let initial;
  if (typeof selector === 'string') {
    // Create new DOM element if element is a string containing html (<div>example</div>)
    if (selector.trim().charAt(0) === '<') {
      const template = document.createElement('template');
      template.innerHTML = selector.trim();
      const fragment = template.content;
      initial = fragment.firstChild;
    } else {
      initial = (context.document && context.document.querySelectorAll(selector)) || false;
    }
  } else {
    initial = selector;
  }

  const instance = new Dom(context);

  return initial ? instance.add(initial) : instance;
}
