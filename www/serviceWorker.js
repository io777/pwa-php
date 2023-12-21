// BrowserPetsWorker.js

self.addEventListener('push', (event) => {
    let pushMessageJSON = event.data.json();

    console.log(pushMessageJSON);

    // Our server puts everything needed to show the notification
    // in our JSON data.
    event.waitUntil(self.registration.showNotification('123', {
        body: '123'
    }));
});