import React from 'react';


const ModalWithVideo = props => {

    const { isVisible, close } = props;

    function createVideo() {
        const width = window.innerWidth < 620 ? window.innerWidth - 50 : 560;
        const height = width * 0.5625;
        return {__html:
        //'<iframe width="560" height="315" frameborder="0" data-video="//www.youtube.com/embed/OlJ9VdY9dig" allowfullscreen></iframe>'
        '<iframe width="' + width + '" height="' + height + '" src="https://www.youtube.com/embed/OlJ9VdY9dig?rel=0&amp;showinfo=0" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>'
    }; };

    return (

        <div id="modalHowUnitMove" className="modal fade" style={ {display: isVisible ? "block":"none", opacity: isVisible ? 1:0} }>
            <div className="modal-dialog">
                <div className="modal-content">

                    <div className="modal-body">
                        <a onClick={ () => close(false) } type="button" className="youtube-stop">&times;</a>
                        <br />
                        <span style={ { fontSize:'12px' } }>
                            Video was made for the old game version, but rules are still the same.
                        </span>
                        <br />
                        { isVisible && <div dangerouslySetInnerHTML={ createVideo() } /> }
                    </div>
                    <div className="modal-footer"><a onClick={ () => close(false) } type="button" className="btn btn-primary youtube-stop">Close</a></div>
                </div>
            </div>
        </div>
    );
};

export default ModalWithVideo;