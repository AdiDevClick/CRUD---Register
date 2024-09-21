/**
 * ERROR HANDLER CONFIG FILE
 */
////////////////////////////////////////////////////////////
/** MESSAGES */

// Email error message :
export const invalidEmailMessage = `Votre email est invalide 
                exemple valide : monEmail@mail.fr`

// Password invalid message :
export const invalidPwMessage = 'Vos mots de passes ne sont pas identiques'

// No space allowed message :
export const noSpaceAllowedMessage = 'Veuillez ne pas utiliser d\'espace'

// Only integer allowed message :
export const notANumberError = 'Seuls les nombres sont autorisés'

// Empty inputs message
export const emptyAlert = 'Un ou plusieurs champs sont vides'

/** MESSAGES END ********/
////////////////////////////////////////////////////////////
/** INPUTS TO LISTEN TO */

export const inputsToListen = 'input, textarea'

/** INPUTS TO LISTEN TO END ********/
////////////////////////////////////////////////////////////
/** REGEX */

// Can only use this chars :
export const allowedSpecialChars = new RegExp('^[\\w\\s,.:;_?\'!\\"*()~&éèêëàâäôöûüùçÀ-]+$')

// Email can only be email@email.co :
export const emailInputRegex = new RegExp("([a-z0-9A-Z._-]+)@([a-z0-9A-Z_-]+)\\.([a-z\.]{2,6})$")

/** REGEX END ********/
////////////////////////////////////////////////////////////
/** ALERT */

// Alert class :
export const alertClass = 'alert-error hidden'

// Input error class :
export const inputErrorClass = 'input_error'

/** ALERT END ********/
////////////////////////////////////////////////////////////