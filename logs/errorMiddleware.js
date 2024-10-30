export const logError = err => {
    console.log("ERROR: " + String(err))
}

export const errorLoggerMiddleware = (err, req, res, next) => {
    logError(err)
    next(err)
}

export const returnErrorMiddleware = (err, req, res, next) => {
    res.status(err.statusCode || 500)
        .send(err.message)
}

/**
 * 
 */
// export default {
//     logError,
//     errorLoggerMiddleware,
//     returnErrorMiddleware
// }