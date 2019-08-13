import Vue from 'vue'
import { AclInstaller, AclCreate, AclRule } from 'vue-acl'
import router from './index'
import store from '~/store/index'
 
Vue.use(AclInstaller)
 
export default new AclCreate({
  initial: 'public',
  notfound: '/profile',
  router,
  acceptLocalRules: true,
  globalRules: {
    admin: new AclRule('admin').generate(),
    digprococa: new AclRule('digprococa').or('admin').generate(),
    digcoin: new AclRule('digcoin').or('admin').or('digprococa').generate(),
    fonadin: new AclRule('fonadin').or('admin').generate(),
    oii: new AclRule('oii').or('admin').generate(),
    public: new AclRule('public').or('admin').or('digprococa').or('digcoin').or('fonadin').or('oii').generate(),
  },
  middleware: async acl => {
    if (store.getters['auth/user'] != null) {
      acl.change(store.getters['auth/user'].roles[0].name)
    } else {
      acl.change('public')
    }
  }
})