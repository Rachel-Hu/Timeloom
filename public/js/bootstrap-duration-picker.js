(function iife($) {

  $.DurationPicker = function DurationPicker(mainElement, options) {

    const defaults = {
      translations: {
        week: 'week',
        day: 'day',
        hour: 'hour',
        weeks: 'weeks',
        days: 'days',
        hours: 'hours',
      },
      showDays: true,
    };

    const plugin = this;

    plugin.settings = {};

    const mainInput = $(mainElement);

    plugin.init = function init() {
      plugin.settings = $.extend({}, defaults, options);

      const mainInputReplacer = $('<div>', {
        class: 'bdp-input',
        html: [
          buildDisplayBlock('weeks', !plugin.settings.showDays),
          buildDisplayBlock('days', !plugin.settings.showDays),
          buildDisplayBlock('hours', false, plugin.settings.showDays ? 23 : 99999),
        ],
      });

      mainInput.after(mainInputReplacer).hide();

      if (mainInput.val() === '') mainInput.val(0);
      setValue(mainInput.val(), true);
    };

    const inputs = [];
    const labels = [];
    const disabled = mainInput.hasClass('disabled') ||
      mainInput.attr('disabled') === 'disabled';

    let weeks = 0;
    let days = 0;
    let hours = 0;

    //
    // private methods
    //
    function translate(key) {
      return plugin.settings.translations[key];
    }

    function updateWordLabel(value, label) {
      const text = value === 1 ? label.substring(0, label.length - 1) : label;
      labels[label].text(translate(text));
    }

    function updateUI(isInitializing = false) {
      const total = hours + days * 24 + weeks * 24 * 7;
      mainInput.val(total);
      mainInput.change();

      updateWordLabel(weeks, 'weeks');
      updateWordLabel(days, 'days');
      updateWordLabel(hours, 'hours');

      inputs.weeks.val(weeks);
      inputs.days.val(days);
      inputs.hours.val(hours);

      if (typeof plugin.settings.onChanged === 'function') {
        plugin.settings.onChanged(mainInput.val(), isInitializing);
      }
    }

    function durationPickerChanged() {
      weeks = parseInt(inputs.weeks.val(), 10) || 0;
      days = parseInt(inputs.days.val(), 10) || 0;
      hours = parseInt(inputs.hours.val(), 10) || 0;
      updateUI();
    }

    function buildDisplayBlock(id, hidden, max) {
      const input = $('<input>', {
        class: 'form-control input-sm',
        type: 'number',
        min: 0,
        value: 0,
        disabled,
      }).change(durationPickerChanged);

      if (max) {
        input.attr('max', max);
      }
      inputs[id] = input;

      const label = $('<div>', {
        id: `bdp-${id}-label`,
        text: translate(id),
      });
      labels[id] = label;

      return $('<div>', {
        class: `bdp-block ${hidden ? 'hidden' : ''}`,
        html: [input, label],
      });
    }

    function setValue(value, isInitializing) {
      mainInput.val(value);

      let total = parseInt(value, 10);
      hours = total % 24;
      total = Math.floor(total / 24);

      if (plugin.settings.showDays) {
        days = total % 7;
        weeks = Math.floor(total / 7);
      } else {
        hours = total;
        days = 0;
      }

      updateUI(isInitializing);
    }

    function getValue() { return mainInput.val(); }

    //
    // public methods
    //
    plugin.getValue = function() {
      return getValue();
    };

    plugin.setValue = function(value) {
      setValue(value, true);
    };

    plugin.destroy = function () {
      mainInput.next('.bdp-input').remove();
      mainInput.data('durationPicker', null).show();
    };

    plugin.init();
  };

  // eslint-disable-next-line no-param-reassign
  $.fn.durationPicker = function durationPicker(options) {
    return this.each(function() {
      if (undefined === $(this).data('durationPicker')) {
        const plugin = new $.DurationPicker(this, options);
        $(this).data('durationPicker', plugin);
      }
    });
  };

})(jQuery); // eslint-disable-line no-undef
