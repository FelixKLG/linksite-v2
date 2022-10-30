import '../../css/theme.css';

function Theme({children}) {
    return (
        <div className="bg-gradient-to-bl from-indigo-800 via-indigo-700 to-violet-800 background-animate">
            <div className='bg-slate-900/30'>
                <div className="min-h-screen min-w-screen">
                    {children}
                </div>
            </div>
        </div>
    );
}

export default Theme;
