import { createElement } from "../functions/dom.js";
import { Carousel } from "./Carousel.js"
// import * as errorMiddleware from "../../logs/errorMiddleware.js";

export class YoutubePlayer 
{

    player = []
    event = []
    done = true
    /** @type {AbortController} */
    #controller 

    /**
     * @param {Carousel} carousel
     */
    constructor(carousel) {
        this.carousel = carousel
        this.videoContainer = carousel.container
        // Transformation de la div en iFrame
        this.#iFrameCreation()
        // Evènements
        this.#iFramesListener()
    }

    /**
     * Permet d'initialiser l'array qui contiendra
     * les informations essentielles de l'API -
     */
    #identifyPlayers() {
        const containers = this.videoContainer.querySelectorAll('.player')
        for (const container of containers) {
            if (!this.player[container.id] && container.tagName !== 'IFRAME') {
                this.player[container.id] = {
                    element: container,
                    id: container.id
                }
            }
        }
    }

    /**
     * Création de l'iFrame pour chaque vidéo en utilisant l'API Youtube -
     */
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
                window.onYouTubeIframeAPIReady = this.onYouTubeIframeAPIReady.bind(this)
            // }, {once: true})
            const firstScriptTag = document.getElementsByTagName('script')[0]
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag)
        } else {
            this.onYouTubeIframeAPIReady()
        }
    }

    /**
     * Renvoi le this.player.event ayant retourné une erreur -
     * @param {Object} e 
     */
    onPlayerError(e) {
        console.log('error => \n', e)

        // console.log(errorMiddleware)
    }

    // get getPlayId() {
    //     return this.id.push(this.player.id)
    // }

    /**
     * Permet de pause l'animation lors d'un mouse hover -
     * La vidéo sera mise sur LECTURE -
     * @param {PointerEvent} e 
     */
    #onHover(e, element, item) {
        e.preventDefault()
        if (!this.player[element.id]) return
        // if (element.tagName === 'DIV') return
        // if (element.tagName === 'IFRAME') return

        // let backgroundBlur = document.querySelector('.js-background')
        // if (!backgroundBlur) {
        //     backgroundBlur = createElement('div', {
        //         class: "dropdown-menu-background js-background"
        //     })
        //     this.videoContainer.insertAdjacentElement('afterbegin', backgroundBlur)
        // }
        // backgroundBlur.style.visibility = 'visible'
        // backgroundBlur.style.zIndex = '10'
        let data
        const player = this.player[element.id]
        if (player.event) data = player.event.data
        if (player.event && data !== 1 && this.done) {
            if (this.carousel.getLoadingBar) this.carousel.getLoadingBar.style.animationPlayState = 'paused'
            this.done = false
            player.player.playVideo()
            player.event.target.setPlaybackQuality('hd1080')
        }
        this.done = false
        item.addEventListener('mouseleave', e => this.#onPointerOut(e, element), { once: true, signal: this.#controller.signal })
    }

    /**
     * Unpause la vidéo quand le pointer est enlevé de l'item
     * @param {PointerEvent} e
     * @param {HTMLElement} element
     */
    #onPointerOut(e, element) {
        console.log('out')
        e.preventDefault()
        // if (this.player[element.id].element.tagName === 'IFRAME') return

        // let backgroundBlur = document.querySelector('.js-background')
        // backgroundBlur.style.visibility = 'hidden'
        let data
        const player = this.player[element.id]
        if (player.event) data = player.event.data
        if (player.event && data === 1 && !this.done) {
            this.carousel.setPromiseArray = []
            player.player.pauseVideo()
            this.done = true
        }
        this.done = true
    }

    // 4. The API will call this function when the video player is ready.
    onPlayerReady(event) {
        event.target.playVideo()
        event.target.setPlaybackQuality('hd1080')
        event.target.pauseVideo()
        this.player[event.target.options.videoId].event = event
    }

    // 5. The API calls this function when the player's state changes.
    //    The function indicates that when playing a video (state=1),
    //    the player should play for six seconds and then stop.
    onPlayerStateChange(event) {
        this.player[event.target.options.videoId].event = event
        if (event.data === YT.PlayerState.BUFFERING) {
            event.target.setPlaybackQuality('hd1080')
        }
    }

    /**
     * Création de l'iFrame -
     * Sauvegarde de l'objet dans "this.player"
     */
    onYouTubeIframeAPIReady() {
        this.#identifyPlayers()
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
                    'mute': '0',            // Mute sound
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
            this.player[container].player = player
        }
    }

    /**
     * Création d'un abort controller pour utilisation ultérieure -
     * Création d'un mouseenter event qui permettra
     * la gestion suite à un hover de l'utilisateur -
     */
    #iFramesListener() {
        if (!this.#controller) {
            this.#controller = new AbortController()
        } else {
            // this.#controller.abort('Je coupe les anciens events')
            // this.deleteIFrames
            // this.#controller = new AbortController()
        }

        this.carousel.items.forEach(item => {
            // !! IMPORTANT !! Reduit la hauteur de la zone hover pour une meilleure détection
            const hoveredItem = item.querySelector('.js-href')
            // Retourne si l'on a trouvé une vidéo
            const foundPlayer = item.querySelector('.player')
            if (foundPlayer) {
                hoveredItem.addEventListener('mouseenter', e => this.#onHover(e, foundPlayer, hoveredItem), { signal: this.#controller.signal })
            }
        })
    }

    get videoStatus() {
        return this.done
    }
    /** Supprime tous les players et les events */
    get deleteIFrames() {
        for (const container in this.player) {
            this.player[container].player.destroy()
        }
        this.#controller.abort()
    }

    get identifyPlayers() {
        return this.#identifyPlayers()
    }
    /** Retourne la création  */
    get APIReady() {
        return this.onYouTubeIframeAPIReady()
    }
}