class ProgressiveStepButton {
    
    #r
    constructor(circumference) {
        this.#r = circumference
    }

    #calculateDasharray(r) {
        return Math.PI * r * 2
    }

    #calculateDashoffset(percentageShown, circumference) {
        return ((100 - percentageShown) / 100) * circumference
    }
}