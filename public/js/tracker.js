/**
 * TAVP Analytics Tracker
 * Lightweight page view and event tracker.
 */
(function() {
  'use strict';

  var config = window.tavpAnalyticsConfig || {
    endpoint: '/api/analytics',
    sessionRecording: false
  };

  // Track page view on load
  function trackPageView() {
    var data = {
      url: window.location.href,
      path: window.location.pathname,
      title: document.title,
      referrer: document.referrer || '',
      viewport: window.innerWidth + 'x' + window.innerHeight,
      platform: navigator.platform,
      language: navigator.language,
      timestamp: new Date().toISOString()
    };

    sendToEndpoint('/track', data);
  }

  // Track custom events
  function trackEvent(category, action, label) {
    var data = {
      event: true,
      category: category,
      action: action,
      label: label || '',
      url: window.location.href,
      timestamp: new Date().toISOString()
    };

    sendToEndpoint('/event', data);
  }

  // Send data to endpoint
  function sendToEndpoint(path, data) {
    if (navigator.sendBeacon) {
      navigator.sendBeacon(config.endpoint + path, JSON.stringify(data));
    } else {
      var xhr = new XMLHttpRequest();
      xhr.open('POST', config.endpoint + path, true);
      xhr.setRequestHeader('Content-Type', 'application/json');
      xhr.send(JSON.stringify(data));
    }
  }

  // Expose API
  window.tavpAnalytics = {
    pageview: trackPageView,
    event: trackEvent
  };

  // Auto-track page view
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', trackPageView);
  } else {
    trackPageView();
  }
})();
