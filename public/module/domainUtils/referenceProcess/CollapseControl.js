export const hideCollapse = (collapse)=>{
    let bsCollapse = new bootstrap.Collapse(collapse, {
        toggle: false
    })
    bsCollapse.hide()
}