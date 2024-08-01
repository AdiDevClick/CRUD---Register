import { Carousel } from "./Carousel.js"

export class YoutubePlayer 
{

    player = []
    event = []
    done = true

    /**
     * @param {Carousel} carousel
     */
    constructor(carousel) {
        this.carousel = carousel
        this.videoContainer = carousel.container

        const containers = this.videoContainer.querySelectorAll('.player')
        for (const container of containers) {
            this.player[container.id] = {
                element: container, 
                id: container.id
            }
        }

        this.#iFrameCreation()

        carousel.items.forEach(item => {
            const foundPlayer = item.querySelector('.player')
            if (foundPlayer) {
                item.addEventListener('mouseenter', e => this.onHover(foundPlayer))
                item.addEventListener('mouseleave', e => this.onPointerOut(foundPlayer))
            }
        })
    }

    #iFrameCreation() {
        const iframe = document.getElementById('videoIFrame')

        if (!iframe) {
            const tag = document.createElement('script')

            tag.src = 'https://www.youtube.com/iframe_api'
            tag.setAttribute('id', 'videoIFrame')
            tag.type = 'text/javascript'
            tag.loading = 'lazy'
            tag.referrerPolicy = 'no-referrer'
            // tag.type =  'image/svg+xml'
            // this.videoContainer.addEventListener('load', e => {
                // console.log(e)
            window.onYouTubeIframeAPIReady = this.onYouTubeIframeAPIReady.bind(this)
            // }, {once: true})
            const firstScriptTag = document.getElementsByTagName('script')[0]
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag)
        } else {
            this.onYouTubeIframeAPIReady()
            // console.log(e => this.loadVideo()) 
            // window.onYouTubeIframeAPIReady = this.onYouTubeIframeAPIReady.bind(this)
        }
    }

    onPlayerError(e) {
        console.log(e)
        console.log('erreur')
    }

    // get getPlayId() {
    //     return this.id.push(this.player.id)
    // }

    /**
     * Permet de pause l'animation lors d'un mouse hover
     * @param {PointerEvent} e 
     */
    onHover(element) {
        const player = this.player[element.id]
        const data = player.event.data
        if (player.event && data !== 1 && this.done) {
            if (this.carousel.getLoadingBar) this.carousel.getLoadingBar.style.animationPlayState = 'paused'
            this.done = false
            player.player.playVideo()
            player.event.target.setPlaybackQuality('hd1080')
        }
        this.done = false
    }

    /**
     * Relance l'animation quand le pointer est enlevé de l'item
     * @param {PointerEvent} e 
     */
    onPointerOut(element) {
        const player = this.player[element.id]
        // console.log(element.id)
        // console.log(player)

        const data = player.event.data
        if (player.event && data === 1 && !this.done) {
            this.carousel.setPromiseArray = []
            player.player.pauseVideo()
            this.done = true
        }
        this.done = true
    }

    // 4. The API will call this function when the video player is ready.
    onPlayerReady(event) {
        console.log(this.player)
        console.log(event.target)
        event.target.playVideo()
        event.target.setPlaybackQuality('hd1080')
        event.target.pauseVideo()
        // this.player[event.target.o.id].event = event
        this.player[event.target.l.id].event = event
    }

    // 5. The API calls this function when the player's state changes.
    //    The function indicates that when playing a video (state=1),
    //    the player should play for six seconds and then stop.
    onPlayerStateChange(event) {
        // this.player[event.target.o.id].event = event
        this.player[event.target.l.id].event = event
        if (event.data === YT.PlayerState.BUFFERING) {
            event.target.setPlaybackQuality('hd1080')
        }
    }

    onYouTubeIframeAPIReady() {
        for (const container in this.player) {
            const player = new YT.Player(container, {
                // width: '360',
                width: '100%',
                height: '100%',
                allowfullscreen: '1',
                fullscreen: '1',
                // height: '720',
                videoId: container,
                playerVars: {
                    'mute': '1',            // Mute sound
                    'autoplay': '0',        // Auto-play the video on load
                    'controls': '0' ,       // Show pause/play buttons in player
                    'showinfo': '0',        // Hide the video title
                    'enablejsapi': '1',     // Enable JavaScript controls
                    'loop': '1',            // Run the video in a loop
                    'modestbranding': '1',  // Hide the Youtube Logo
                    'rel': '0',             // Disables video suggestions at the end of a video
                    'iv_load_policy': '3',  // Hide the Video Annotations
                    'autohide': '0',        // Hide video controls when playing
                    // fs: 0,              // Hide the full screen button
                },
                events: {
                    'onReady': this.onPlayerReady.bind(this),
                    'onStateChange': this.onPlayerStateChange.bind(this),
                    'onError': this.onPlayerError.bind(this),
                    'onPlaybackQualityChange': e => {
                        e.target.setPlaybackQuality('hd1080')
                    }
                }
            })
            // this.player[container].player = {...player}
            this.player[container].player = player
        }
    }

    get videoStatus() {
        return this.done
    }
}