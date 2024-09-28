import { createElement } from "../functions/dom.js";
import { Carousel } from "./Carousel.js"
import * as errorMiddleware from "../../logs/errorMiddleware.js";

export class YoutubePlayer 
{

    player = []
    event = []
    done = true
    #item = []
    #foundPlayer = []

    /**
     * @param {Carousel} carousel
     */
    constructor(carousel) {
        console.log('YoutubePlayer initialisé');
        this.carousel = carousel
        this.videoContainer = carousel.container

        // this.#identifyPlayers()
        
        console.log(this.player)
        this.#iFrameCreation()
        // for (let i = 0; i < this.player.length; i++) {
        //     console.log(this.player[i])
        //     const foundPlayer = this.videoContainer.getElementById(this.player[i].id)
        //     console.log(foundPlayer)
        //     if (foundPlayer) {
        //         this.player[i].addEventListener('mouseenter', this.onHover(foundPlayer))
        //         this.player[i].addEventListener('mouseleave', this.onPointerOut(foundPlayer))
        //     }
        // }
        // for (const player in this.player) {
        //     const foundPlayer = this.videoContainer.querySelector(`#${player}`)
        //     console.log(foundPlayer)
        //     if (foundPlayer) {
        //         foundPlayer.addEventListener('mouseenter', this.onHover)
        //         foundPlayer.addEventListener('mouseleave', this.onPointerOut)
        //     }
        // }
        
        // this.player.forEach(player => {
        //     console.log(player)
        //     const foundPlayer = player.querySelector('.player')
        //     if (foundPlayer.id) {
        //         player.addEventListener('mouseenter', e => this.onHover(foundPlayer))
        //         player.addEventListener('mouseleave', e => this.onPointerOut(foundPlayer))
        //     }
        // })

        this.carousel.items.forEach(item => {
            this.#item = item
            const foundPlayer = this.#item.querySelector('.player')
            if (foundPlayer) {
                this.#item.addEventListener('mouseenter', e => this.onHover(foundPlayer))
                this.#item.addEventListener('mouseleave', e => this.onPointerOut(foundPlayer))
            }
        })
    }

    /**
     * Permet d'initialiser l'array qui contiendra
     * les informations essentielles de l'API -
     */
    #identifyPlayers() {
        const containers = this.videoContainer.querySelectorAll('.player')
        for (const container of containers) {
            this.player[container.id] = {
                element: container,
                id: container.id
            }
        }
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
            this.videoContainer.addEventListener('load', e => {
                window.onYouTubeIframeAPIReady = this.onYouTubeIframeAPIReady.bind(this)
            }, {once: true})
            const firstScriptTag = document.getElementsByTagName('script')[0]
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag)
        } else {
            console.log('else')
            // this.youtubeAPI = window.onYouTubeIframeAPIReady = this.onYouTubeIframeAPIReady.bind(this)
            this.onYouTubeIframeAPIReady()
            // console.log(e => this.loadVideo()) 
            // window.onYouTubeIframeAPIReady = this.onYouTubeIframeAPIReady.bind(this)
        }
    }

    /**
     * Renvoi le this.player.event ayant retourné une erreur -
     * @param {Object} e 
     */
    onPlayerError(e) {
        console.log(e)

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
    onHover(element) {
        this.#item.removeEventListener('mouseenter', this.onHover)
        console.log('pointer in => \n', element)
        let backgroundBlur = document.querySelector('.js-background')
        if (!backgroundBlur) {
            backgroundBlur = createElement('div', {
                class: "dropdown-menu-background js-background"
            })
            this.videoContainer.insertAdjacentElement('afterbegin', backgroundBlur)
        }
        console.log(backgroundBlur)
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
    }

    /**
     * Relance l'animation quand le pointer est enlevé de l'item
     * @param {PointerEvent} e 
     */
    onPointerOut(element) {
        console.log('pointer out => \n', element)
        let backgroundBlur = document.querySelector('.js-background')
        backgroundBlur.style.visibility = 'hidden'
        let data
        const player = this.player[element.id]
        console.log(player.event.target.__proto__.getVideoEmbedCode)
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
        // console.log(this.player)
        // console.log(event.target.options)
        // console.log(event)
        // console.log(event.target.getInternalApiInterface)
        event.target.playVideo()
        event.target.setPlaybackQuality('hd1080')
        event.target.pauseVideo()
        // this.player[event.target.o.id].event = event
        this.player[event.target.options.videoId].event = event
        // this.player[event.target.l.id].event = event
    }

    // 5. The API calls this function when the player's state changes.
    //    The function indicates that when playing a video (state=1),
    //    the player should play for six seconds and then stop.
    onPlayerStateChange(event) {
        // this.player[event.target.o.id].event = event
        console.log(this.player)
        console.log('\n event =>', event)
        // this.player[event.target.l.id].event = event
        this.player[event.target.options.videoId].event = event

        if (event.data === YT.PlayerState.BUFFERING) {
            event.target.setPlaybackQuality('hd1080')
        }
    }

    /**
     * Création de l'iFrame -
     * Sauvegarde de l'objet dans "this.player"
     */
    onYouTubeIframeAPIReady(element) {
        console.log('je suis entré')
        const players = this.player
        this.#identifyPlayers()
        console.log(this.player)
        console.log(players)
        for (const container in this.player) {
            // this.player[container].player.destroy()
            console.log(this.player[container].element.tagName)
            // if (this.player[container].element.tagName === 'DIV') {
                console.log(this.player[container].event)
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
                console.log(player)
                // console.log(this.player[container].player.destroy())
            // }
        }
    }

    get videoStatus() {
        return this.done
    }
    get deleteIFrames() {
        console.log('dans le delete i framles')
        for (const container in this.player) {
            console.log(container)
            this.player[container].player.destroy()
        }
    }

    get identifyPlayers() {
        return this.#identifyPlayers()
    }
}