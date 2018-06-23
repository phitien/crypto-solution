import {log} from '../utils'

export function persitUser(User) {
 localStorage.setItem(`User`, JSON.stringify(User))
}
