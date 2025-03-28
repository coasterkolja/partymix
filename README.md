# Spotify PartyMix
Spotify Partymix is a Spotify Group Session, that is setup by one host. As a host you can login with your Spotify Premium account and let guests join. Everyone may add songs to the queue, but noone except the host can control the currently playing song.

## Planned Features
- [ ] Make Jams joinable by a QR-Code
- [ ] Add Playlist Pool for collection of playlists where the app should queue songs from if the wishlist is emty
- [x] Add song skipping ability for host
- [ ] Add queue editing abilities for host
- [ ] Add voting functionality, to make guest able to remove songs from the queue with a certain amount of downvotes
- [ ] Add song queue cooldown
- [ ] Update UI
- [ ] Add different views for certain things like wishlist, current song, or QR-Code
- [ ] Delete Jam when inactive for too long

## How to run as dev
`npm run dev`
`php artisan queue:work`
`php artisan reverb:start`